<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

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
        // Redirect to login when user is not authenticated
        $this->app['auth']->extend('session', function ($app) {
            return new \Illuminate\Auth\SessionGuard(
                'session',
                $app['auth']->createUserProvider('users'),
                $app['session.store'],
                $app['request']
            );
        });
    }
}