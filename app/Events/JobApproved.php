<?php

namespace App\Events;

use App\Models\Job;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JobApproved
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Job $job)
    {
    }
}
