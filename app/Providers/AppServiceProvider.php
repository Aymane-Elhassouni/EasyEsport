<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\DAO\Interfaces\UserDAOInterface::class,
            \App\DAO\Eloquent\EloquentUserDAO::class
        );

        $this->app->bind(
            \App\Services\Interfaces\AuthServiceInterface::class,
            \App\Services\AuthService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
