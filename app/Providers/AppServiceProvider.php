<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerBladeDirectives();
    }

    private function registerBladeDirectives(): void
    {
        Blade::if('canOrRole', function ($abilities, $roles = 'super-admin')
        {
            $user = auth()->user();
            if (!$user) return false;

            $abilities = is_array($abilities) ? $abilities : [$abilities];
            $roles = is_array($roles) ? $roles : [$roles];

            if (collect($abilities)->isEmpty()) return true;

            foreach ($roles as $role)
            {
                if ($user->hasRole($role)) return true;
            }

            foreach ($abilities as $ability)
            {
                if ($user->can($ability)) return true;
            }

            return false;
        });

        Blade::if('superAdmin', function ()
        {
            return auth()->user() && auth()->user()->hasRole('super-admin');
        });
    }
}
