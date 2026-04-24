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

        $this->app->bind(
            \App\DAO\Interfaces\ProfileDAOInterface::class,
            \App\DAO\Eloquent\EloquentProfileDAO::class
        );

        $this->app->bind(
            \App\Services\Interfaces\ProfileServiceInterface::class,
            \App\Services\ProfileService::class
        );

        $this->app->bind(
            \App\Services\Interfaces\TeamServiceInterface::class,
            \App\Services\TeamService::class
        );

        $this->app->bind(
            \App\Services\Interfaces\DashboardServiceInterface::class,
            \App\Services\DashboardService::class
        );

        $this->app->bind(
            \App\Services\Interfaces\TournamentServiceInterface::class,
            \App\Services\TournamentService::class
        );

        $this->app->bind(
            \App\Services\AdminDashboardService::class,
            \App\Services\AdminDashboardService::class
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
