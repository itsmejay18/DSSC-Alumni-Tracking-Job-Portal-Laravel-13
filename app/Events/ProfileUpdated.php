<?php

namespace App\Events;

use App\Models\AlumniProfile;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProfileUpdated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public AlumniProfile $profile, public array $changes = [])
    {
    }
}
