<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Load migrations from subdirectories
        $this->loadMigrationsFrom(database_path('migrations/01_core'));
        $this->loadMigrationsFrom(database_path('migrations/02_modules'));
    }
}
