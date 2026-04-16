<?php

namespace App\Providers;

use App\Events\EmployerRegistered;
use App\Events\JobApplied;
use App\Events\JobApproved;
use App\Events\ProfileUpdated;
use App\Listeners\LogUserActivity;
use App\Listeners\SendNewJobNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        JobApplied::class => [
            LogUserActivity::class,
        ],
        ProfileUpdated::class => [
            LogUserActivity::class,
        ],
        EmployerRegistered::class => [
            LogUserActivity::class,
        ],
        JobApproved::class => [
            LogUserActivity::class,
            SendNewJobNotification::class,
        ],
    ];
}
