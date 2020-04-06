<?php

namespace PockDoc\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'PockDoc\Model' => 'PockDoc\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        \Auth::extend('fcm_id', function($app, $name, array $config) {
            return new \PockDoc\Auth\FCMIdGuard(\Auth::createUserProvider($config['provider']), $app['request']);
        });
    }
}
