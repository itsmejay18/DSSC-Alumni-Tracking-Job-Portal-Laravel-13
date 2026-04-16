<?php

namespace App\Events;

use App\Models\Employer;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmployerRegistered
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Employer $employer)
    {
    }
}
