<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\NotificationService;
use App\Services\ReportService;
use Illuminate\Console\Command;

class SendWeeklyReport extends Command
{
    protected $signature = 'portal:send-weekly-report';

    protected $description = 'Generate and email the weekly admin summary report.';

    public function handle(ReportService $reportService, NotificationService $notificationService): int
    {
        $admins = User::query()->admins()->approved()->get();

        if ($admins->isEmpty()) {
            $this->warn('No approved admins found.');

            return self::SUCCESS;
        }

        $report = $reportService->generate('employment_rate', $admins->first(), ['scope' => 'weekly'], 'json');

        foreach ($admins as $admin) {
            $notificationService->sendWeeklySummary($admin, $report);
        }

        $this->info('Weekly report generated and dispatched to admins.');

        return self::SUCCESS;
    }
}
