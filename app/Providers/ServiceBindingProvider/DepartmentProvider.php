<?php

namespace App\Providers\ServiceBindingProvider;

use App\Modules\Department\Repository\DepartmentRepositoryInterface;
use App\Modules\Department\Repository\Eloquent\DepartmentRepository;
use Illuminate\Support\ServiceProvider;

class DepartmentProvider extends ServiceProvider
{

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}