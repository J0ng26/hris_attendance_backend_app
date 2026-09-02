<?php

namespace App\Providers\ServiceBindingProvider;

use App\Modules\ActivityLog\Repository\ActivityLogRepositoryInterface;
use App\Modules\ActivityLog\Repository\Eloquent\ActivityLogRepository;
use Illuminate\Support\ServiceProvider;

class ActivityLogProvider extends ServiceProvider
{

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ActivityLogRepositoryInterface::class, ActivityLogRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}