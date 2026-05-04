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

        $this->app->bind(
            \App\DAO\Interfaces\AnnouncementDAOInterface::class,
            \App\DAO\Eloquent\EloquentAnnouncementDAO::class
        );

        $this->app->bind(
            \App\Services\Interfaces\AnnouncementServiceInterface::class,
            \App\Services\AnnouncementService::class
        );

        $this->app->bind(
            \App\Services\Interfaces\MatchServiceInterface::class,
            \App\Services\MatchService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('components.sidebar', function ($view) {
            $user = auth()->user();

            $teamsRoute      = route('teams');
            $dashboardRoute  = route('home');
            $dashboardActive = false;

            $isAdmin    = $user->hasRole('admin') || $user->hasRole('super_admin');
            $isPlayer   = $user->hasRole('player');
            $isCaptain  = $user->hasRole('captain');

            if ($user->hasRole('captain')) {
                $team = $user->captainOf()->latest()->first();
                if ($team) $teamsRoute = route('teams.manage', $team->slug);
            } elseif ($user->hasRole('player')) {
                $membership = $user->teamMemberships()->with('team')->latest()->first();
                if ($membership) $teamsRoute = route('teams.manage', $membership->team->slug);
            }

            if ($user->hasRole('super_admin')) {
                $dashboardRoute  = route('admin.system.dashboard');
                $dashboardActive = request()->routeIs('admin.system.dashboard');
            } elseif ($user->hasRole('admin')) {
                $dashboardRoute  = route('admin.dashboard');
                $dashboardActive = request()->routeIs('admin.dashboard');
            } else {
                $dashboardRoute  = route('player.dashboard');
                $dashboardActive = request()->routeIs('player.dashboard');
            }

            $view->with(compact('teamsRoute', 'dashboardRoute', 'dashboardActive', 'isAdmin', 'isPlayer', 'isCaptain'));
        });
    }
}
