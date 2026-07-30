<?php

namespace App\Providers;

use MacropaySolutions\Kernel\Support\ServiceProvider;

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
        if (\str_starts_with(\config('app.url'), 'https://')) {
            \app('url')->forceScheme('https');
        }
    }
}
