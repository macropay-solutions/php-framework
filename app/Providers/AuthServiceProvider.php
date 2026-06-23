<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     */
    public function boot(): void
    {
        // Here you may define how you wish users to be authenticated for your framework
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function (Request $request): ?User {
//            $apiToken = $request->input('api_token');
//
//            return (\is_string($apiToken) && $apiToken !== '') ?
//                User::query()->where('api_token', \hash('sha256', $apiToken))->first() :
//                null;
//            you need to implement this according to your needs.
            return null;
        });
    }
}
