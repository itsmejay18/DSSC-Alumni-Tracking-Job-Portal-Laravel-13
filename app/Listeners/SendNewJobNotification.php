<?php

namespace App\Listeners;

use App\Events\JobApproved;
use App\Services\MatchingService;

class SendNewJobNotification
{
    public function __construct(protected MatchingService $matchingService)
    {
    }

    public function handle(JobApproved $event): void
    {
        $this->matchingService->calculateForJob($event->job->loadMissing(['jobCategory', 'employer']));
    }
}
