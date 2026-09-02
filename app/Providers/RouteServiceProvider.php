<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
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
        foreach (glob(base_path('routes/web/*.php')) as $routeFile) {
            Route::middleware(['web', 'auth:sanctum'])
                ->prefix('/api')->group($routeFile);
        }
    }
}