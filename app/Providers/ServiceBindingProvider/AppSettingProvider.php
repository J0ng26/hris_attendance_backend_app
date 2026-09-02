<?php

namespace App\Providers\ServiceBindingProvider;

use App\Modules\AppSettings\Repository\AppSettingsRepositoryInterface;
use App\Modules\AppSettings\Repository\Eloquent\AppSettingsRepository;
use Illuminate\Support\ServiceProvider;

class AppSettingProvider extends ServiceProvider
{

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AppSettingsRepositoryInterface::class, AppSettingsRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}