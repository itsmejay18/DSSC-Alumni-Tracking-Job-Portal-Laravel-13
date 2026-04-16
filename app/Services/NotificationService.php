<?php

namespace App\Services;

use App\Mail\ApplicationStatusUpdate;
use App\Mail\JobMatchAlert;
use App\Mail\WeeklyReportMail;
use App\Models\JobApplication;
use App\Models\JobMatch;
use App\Models\Notification;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class NotificationService
{
    public function createNotification(
        ?User $user,
        string $type,
        string $title,
        string $message,
        array $data = [],
        bool $emailSent = false,
    ): Notification {
        return Notification::query()->create([
            'user_id' => $user?->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data ?: null,
            'sent_at' => now(),
            'email_sent' => $emailSent,
        ]);
    }

    public function sendJobMatchAlert(User $alumni, JobMatch $match): Notification
    {
        $notification = $this->createNotification(
            $alumni,
            'job_matched',
            'New recommended job found',
            "{$match->job?->title} matches your profile with a {$match->match_score}% score.",
            [
                'job_id' => $match->job_id,
                'route' => route('alumni.jobs.show', $match->job_id),
            ],
            true
        );

        Mail::to($alumni->email)->queue(new JobMatchAlert($match));

        return $notification;
    }

    public function sendApplicationStatus(JobApplication $application): Notification
    {
        $notification = $this->createNotification(
            $application->alumni,
            'application_status',
            'Application status updated',
            "Your application for {$application->job?->title} is now {$application->status}.",
            [
                'job_id' => $application->job_id,
                'application_id' => $application->id,
                'route' => route('alumni.applications.index'),
            ],
            true
        );

        Mail::to($application->alumni?->email)->queue(new ApplicationStatusUpdate($application));

        return $notification;
    }

    public function sendEmployerApproved(User $employerUser): Notification
    {
        $notification = $this->createNotification(
            $employerUser,
            'employer_approved',
            'Employer account approved',
            'Your company account is now approved. You can publish jobs immediately.',
            ['route' => route('employer.dashboard')],
            true
        );

        Mail::raw(
            'Your employer account has been approved. You may now log in and publish job opportunities.',
            function ($message) use ($employerUser): void {
                $message->to($employerUser->email)->subject('Employer Account Approved');
            }
        );

        return $notification;
    }

    public function sendWelcome(User $user): void
    {
        Mail::raw(
            'Welcome to the DSSC Alumni Tracking & Job Portal. Complete your profile to start receiving job matches.',
            function ($message) use ($user): void {
                $message->to($user->email)->subject('Welcome to DSSC Alumni Portal');
            }
        );
    }

    public function sendWeeklySummary(User $admin, Report $report): Notification
    {
        $notification = $this->createNotification(
            $admin,
            'system_alert',
            'Weekly alumni report generated',
            'The weekly summary report is ready for review.',
            [
                'report_id' => $report->id,
                'route' => route('admin.reports.download', $report),
            ],
            true
        );

        Mail::to($admin->email)->queue(new WeeklyReportMail($report));

        return $notification;
    }

    public function archiveOldNotifications(int $days = 30): int
    {
        $notifications = Notification::query()
            ->where('created_at', '<', now()->subDays($days))
            ->orderBy('created_at')
            ->get();

        if ($notifications->isEmpty()) {
            return 0;
        }

        Storage::disk('local')->put(
            'archives/notifications-'.now()->format('YmdHis').'.json',
            $notifications->toJson(JSON_PRETTY_PRINT)
        );

        Notification::query()->whereKey($notifications->pluck('id'))->delete();

        return $notifications->count();
    }

    public function sendBulk(Collection $users, string $type, string $title, string $message, array $data = []): int
    {
        $count = 0;

        foreach ($users as $user) {
            $this->createNotification($user, $type, $title, $message, $data);
            $count++;
        }

        return $count;
    }
}
