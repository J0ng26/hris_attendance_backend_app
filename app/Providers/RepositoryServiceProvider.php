<?php

namespace App\Providers;

use App\Modules\BaseRepository;
use App\Modules\EloquentRepositoryInterface;
use App\Modules\Otp\Repository\Eloquent\OtpRepository;
use App\Modules\Otp\Repository\OtpRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(OtpRepositoryInterface::class, OtpRepository::class);

        $providersPath = app_path('Providers/ServiceBindingProvider');

        foreach (glob($providersPath . '/*.php') as $file) {
            $class = 'App\\Providers\\ServiceBindingProvider\\' . basename($file, '.php');

            if (class_exists($class)) {
                $this->app->register($class);
            }
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
