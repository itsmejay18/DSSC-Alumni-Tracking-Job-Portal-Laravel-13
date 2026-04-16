<?php

namespace App\Console;

use App\Console\Commands\ArchiveNotifications;
use App\Console\Commands\CalculateJobMatches;
use App\Console\Commands\CloseExpiredJobs;
use App\Console\Commands\SendWeeklyReport;
use App\Models\Job;
use App\Services\NotificationService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        CalculateJobMatches::class,
        CloseExpiredJobs::class,
        SendWeeklyReport::class,
        ArchiveNotifications::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        self::scheduleTasks($schedule);
    }

    public static function scheduleTasks(Schedule $schedule): void
    {
        $schedule->call(function (NotificationService $notificationService): void {
            Job::query()
                ->with('employer.user')
                ->where('status', 'approved')
                ->whereDate('application_deadline', '>=', now())
                ->whereDate('application_deadline', '<=', now()->addDays(7))
                ->get()
                ->each(function (Job $job) use ($notificationService): void {
                    $employerUser = $job->employer?->user;

                    if (! $employerUser) {
                        return;
                    }

                    $notificationService->createNotification(
                        $employerUser,
                        'job_expiring',
                        'Job posting nearing deadline',
                        "{$job->title} will expire on {$job->application_deadline?->format('M d, Y')}.",
                        ['job_id' => $job->id, 'route' => route('employer.jobs.show', $job)]
                    );
                });
        })->hourly()->name('jobs:expiring-notifications');

        $schedule->command(CloseExpiredJobs::class)->dailyAt('02:00');
        $schedule->command(CalculateJobMatches::class)->dailyAt('03:00');
        $schedule->command(SendWeeklyReport::class)->mondays()->at('08:00');
        $schedule->command(ArchiveNotifications::class)->monthlyOn(1, '01:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
