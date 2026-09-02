<?php

namespace App\Providers\ServiceBindingProvider;

use App\Modules\UserPermission\Repository\Eloquent\UserPermissionRepository;
use App\Modules\UserPermission\Repository\UserPermissionRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class UserPermissionProvider extends ServiceProvider
{

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserPermissionRepositoryInterface::class, UserPermissionRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}