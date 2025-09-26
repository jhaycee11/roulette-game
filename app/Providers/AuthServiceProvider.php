<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Http\Guards\StaticGuard;
use App\Http\Providers\StaticUserProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Register the static guard
        Auth::extend('static', function ($app, $name, $config) {
            return new StaticGuard(
                $app->make(StaticUserProvider::class),
                $app->make('request')
            );
        });

        // Register the static user provider
        Auth::provider('static', function ($app, $config) {
            return new StaticUserProvider();
        });
    }
}