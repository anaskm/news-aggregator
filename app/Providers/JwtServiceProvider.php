<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class JwtServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\Auth\Contracts\JwtAuth::class, function () {
            return new \App\Services\Auth\Providers\FirebaseJwtProvider(config('jwt.secret'));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
