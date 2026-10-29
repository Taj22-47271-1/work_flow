<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        // Force HTTPS scheme on Cloudflare Tunnel (*.trycloudflare.com) or HTTPS reverse proxies
        // Prevents browser mixed-content warning ("The information you're about to submit is not secure")
        // Leaves local development (localhost / 127.0.0.1) as HTTP
        if (
            str_contains(request()->header('host', ''), 'trycloudflare.com') ||
            request()->header('x-forwarded-proto') === 'https' ||
            str_contains((string)request()->header('cf-visitor', ''), 'https') ||
            request()->isSecure()
        ) {
            URL::forceScheme('https');
        }
    }
}
