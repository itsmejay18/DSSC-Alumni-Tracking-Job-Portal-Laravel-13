<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('portal:about', function () {
    $this->comment('DSSC Alumni Tracking & Job Portal is ready.');
})->purpose('Display a quick portal readiness message.');
