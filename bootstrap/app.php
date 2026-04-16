<?php

use App\Console\Kernel as AppConsoleKernel;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AlumniMiddleware;
use App\Http\Middleware\CheckUserStatus;
use App\Http\Middleware\EmployerMiddleware;
use App\Http\Middleware\TrackLastActivity;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'alumni' => AlumniMiddleware::class,
            'employer' => EmployerMiddleware::class,
            'check.status' => CheckUserStatus::class,
            'track.activity' => TrackLastActivity::class,
        ]);

        $middleware->web(append: [
            TrackLastActivity::class,
        ]);
    })
    ->withCommands([
        __DIR__.'/../app/Console/Commands',
    ])
    ->withSchedule(function (Schedule $schedule): void {
        AppConsoleKernel::scheduleTasks($schedule);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
