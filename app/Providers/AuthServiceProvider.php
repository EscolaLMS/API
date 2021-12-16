<?php

namespace App\Providers;

use App\Extensions\AccessTokenGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        Passport::loadKeysFrom(__DIR__.'/../../storage');

        Auth::extend('access_token', function () {
            // automatically build the DI, put it as reference
            //$userProvider = app(TokenToUserProvider::class);
            $request = app('request');

            return new AccessTokenGuard($request);
        });
    }
}
