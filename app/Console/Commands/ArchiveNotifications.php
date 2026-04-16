<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;

class ArchiveNotifications extends Command
{
    protected $signature = 'portal:archive-notifications {--days=30}';

    protected $description = 'Archive old notifications to storage and remove them from the active table.';

    public function handle(NotificationService $notificationService): int
    {
        $count = $notificationService->archiveOldNotifications((int) $this->option('days'));

        $this->info("Archived {$count} notifications.");

        return self::SUCCESS;
    }
}
