<?php

namespace App\Console\Commands;

use App\Models\Job;
use Illuminate\Console\Command;

class CloseExpiredJobs extends Command
{
    protected $signature = 'portal:close-expired-jobs';

    protected $description = 'Auto-close approved jobs that are past the application deadline.';

    public function handle(): int
    {
        $count = Job::query()
            ->whereIn('status', ['approved', 'pending'])
            ->whereDate('application_deadline', '<', now()->toDateString())
            ->update(['status' => 'expired']);

        $this->info("Expired {$count} job postings.");

        return self::SUCCESS;
    }
}
