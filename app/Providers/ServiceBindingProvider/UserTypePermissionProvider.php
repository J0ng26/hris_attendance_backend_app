<?php

namespace App\Providers\ServiceBindingProvider;

use App\Modules\UserTypePermission\Repository\Eloquent\UserTypePermissionRepository;
use App\Modules\UserTypePermission\Repository\UserTypePermissionRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class UserTypePermissionProvider extends ServiceProvider
{

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserTypePermissionRepositoryInterface::class, UserTypePermissionRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}