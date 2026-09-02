<?php

namespace App\Providers\ServiceBindingProvider;

use App\Modules\Permission\Repository\Eloquent\PermissionRepository;
use App\Modules\Permission\Repository\PermissionRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class PermissionProvider extends ServiceProvider
{

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}