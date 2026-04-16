<?php

namespace App\Events;

use App\Models\JobApplication;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JobApplied
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public JobApplication $application)
    {
    }
}
