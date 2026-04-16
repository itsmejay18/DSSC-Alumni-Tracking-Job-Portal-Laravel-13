<?php

namespace App\Listeners;

use App\Events\EmployerRegistered;
use App\Events\JobApplied;
use App\Events\JobApproved;
use App\Events\ProfileUpdated;
use App\Models\ActivityLog;

class LogUserActivity
{
    public function handle(object $event): void
    {
        match (true) {
            $event instanceof JobApplied => ActivityLog::record(
                $event->application->alumni,
                'create',
                'job',
                'Submitted a job application.',
                [],
                $event->application->only(['job_id', 'status'])
            ),
            $event instanceof ProfileUpdated => ActivityLog::record(
                $event->profile->user,
                'update',
                'alumni',
                'Updated alumni profile.',
                [],
                $event->changes
            ),
            $event instanceof EmployerRegistered => ActivityLog::record(
                $event->employer->user,
                'create',
                'employer',
                'Employer account registration completed.'
            ),
            $event instanceof JobApproved => ActivityLog::record(
                $event->job->approvedBy,
                'update',
                'job',
                'Approved a job posting.',
                [],
                ['job_id' => $event->job->id, 'status' => $event->job->status]
            ),
            default => null,
        };
    }
}
