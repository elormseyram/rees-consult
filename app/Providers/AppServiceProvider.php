<?php

namespace App\Providers;

use App\Services\MailSettings;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        // Overlay the mail credentials stored in admin Settings on top of .env
        // so switching provider (SMTP <-> Resend) needs no deploy.
        (new MailSettings())->apply();
    }
}
