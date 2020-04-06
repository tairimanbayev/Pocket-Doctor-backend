<?php

namespace PockDoc\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use PockDoc\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Validator::extend('phone_number', function ($attribute, $value, $parameters, $validator) {
            if(!$value) {
                return true;
            }
            return preg_match('/^7[0-9]{9}$/', (new User())->setPhoneNumberAttribute($value));
        });
        \Validator::extend('car_number', function ($attribute, $value, $parameters, $validator) {
            if(!$value) {
                return true;
            }
            return preg_match('/^(((KZ)?[0-9]{3}[A-Z]{2,3}[0-9]{2})|[A-Z][0-9]{3}[A-Z]{2,3})$/', $value);
        });
        \Validator::extend('document_number', function ($attribute, $value, $parameters, $validator) {
            if(!$value) {
                return true;
            }
            return preg_match('/^[0-9]{9}$/', $value);
        });
        \Validator::extend('iin', function ($attribute, $value, $parameters, $validator) {
            if(!$value) {
                return true;
            }
            return preg_match('/^[0-9]{12}$/', $value);
        });
        \Validator::extend('driver_license', function ($attribute, $value, $parameters, $validator) {
            if(!$value) {
                return true;
            }
            return preg_match('/^[A-Z]{2}[0-9]{6}$/', $value);
        });

#        URL::forceScheme('https');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
