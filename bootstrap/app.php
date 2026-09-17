<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('youtube:sync-testimonials')->daily();
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'staff' => \App\Http\Middleware\EnsureStaff::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (\Illuminate\Session\TokenMismatchException $e) {
            \Illuminate\Support\Facades\Log::warning('CSRF Token Mismatch (419)', [
                'url'        => request()->fullUrl(),
                'ip'         => request()->ip(),
                'ua'         => request()->userAgent(),
                'referer'    => request()->header('referer'),
                'has_fbclid' => request()->has('fbclid'),
            ]);
        });
    })->create();
