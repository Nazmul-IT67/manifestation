<?php

use App\Console\Commands\ExpireStories;
use App\Http\Middleware\Admin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Support\Facades\Route;
use Illuminate\Console\Scheduling\Schedule;



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware(['web', 'auth', 'admin'])
                ->prefix('admin')
                ->group(function () {
                    require base_path('routes/backend.php');
                });
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'track.activity' => \App\Http\Middleware\TrackUserActivity::class,
            'admin'              => Admin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command(ExpireStories::class)->everyTwoMinutes();
    })
    ->create();
