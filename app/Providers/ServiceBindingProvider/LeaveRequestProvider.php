<?php

namespace App\Providers\ServiceBindingProvider;

use App\Modules\LeaveRequest\Repository\LeaveRequestRepositoryInterface;
use App\Modules\LeaveRequest\Repository\Eloquent\LeaveRequestRepository;
use Illuminate\Support\ServiceProvider;

class LeaveRequestProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(LeaveRequestRepositoryInterface::class, LeaveRequestRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
