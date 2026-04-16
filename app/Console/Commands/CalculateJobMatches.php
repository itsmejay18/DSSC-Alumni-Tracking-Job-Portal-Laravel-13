<?php

namespace App\Console\Commands;

use App\Models\Job;
use App\Models\User;
use App\Services\MatchingService;
use Illuminate\Console\Command;

class CalculateJobMatches extends Command
{
    protected $signature = 'portal:calculate-job-matches {--job_id=} {--alumni_id=}';

    protected $description = 'Calculate smart job matches for alumni and jobs.';

    public function handle(MatchingService $matchingService): int
    {
        // SMART MATCHING: 60% skills + 30% course + 10% experience weighted scoring.
        if ($jobId = $this->option('job_id')) {
            $job = Job::query()->with(['jobCategory', 'employer'])->findOrFail($jobId);
            $count = $matchingService->calculateForJob($job);
            $this->info("Calculated {$count} matches for job #{$jobId}.");

            return self::SUCCESS;
        }

        if ($alumniId = $this->option('alumni_id')) {
            $alumni = User::query()->with(['alumniProfile.course'])->findOrFail($alumniId);
            $count = $matchingService->calculateForAlumni($alumni);
            $this->info("Calculated {$count} matches for alumni #{$alumniId}.");

            return self::SUCCESS;
        }

        $count = $matchingService->recalculateAll();
        $this->info("Calculated {$count} matches across the portal.");

        return self::SUCCESS;
    }
}
