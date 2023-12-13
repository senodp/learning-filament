<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Filament::serving(function(){
            Filament::registerViteTheme('resources/css/filament.css');
        });
    }
}
