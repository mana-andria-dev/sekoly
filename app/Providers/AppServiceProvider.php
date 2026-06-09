<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Date;

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
        Date::setLocale('fr');

        Schema::defaultStringLength(191);
        
        // Charger les migrations tenant uniquement quand on est dans un contexte tenant
        if ($this->app->environment('local') || $this->app->environment('production')) {
            // Cette condition sera vraie quand tenancy est initialisé
            if (tenancy()->initialized) {
                $this->loadMigrationsFrom(database_path('migrations/tenant'));
            }
        }
    }
}
