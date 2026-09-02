<?php

namespace App\Providers\ServiceBindingProvider;

use App\Modules\UserType\Repository\Eloquent\UserTypeRepository;
use App\Modules\UserType\Repository\UserTypeRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class UserTypeProvider extends ServiceProvider
{

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserTypeRepositoryInterface::class, UserTypeRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}