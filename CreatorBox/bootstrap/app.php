<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        // Monthly sponsorship renewals - run on last day of month at 23:59
        $schedule->command('app:sponsorships-renew')->monthlyOn(date('t'), '23:59');

        // Cleanup inactive plans - run on last day of month at 23:59
        $schedule->command('app:cleanup-inactive-plans')->monthlyOn(date('t'), '23:59');
    })
    ->create();
