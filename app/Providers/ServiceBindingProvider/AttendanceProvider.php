<?php

namespace App\Providers\ServiceBindingProvider;

use App\Modules\Attendance\Repository\AttendanceRepositoryInterface;
use App\Modules\Attendance\Repository\Eloquent\AttendanceRepository;
use Illuminate\Support\ServiceProvider;

class AttendanceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AttendanceRepositoryInterface::class, AttendanceRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
