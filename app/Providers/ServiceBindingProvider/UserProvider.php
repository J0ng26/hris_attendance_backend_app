<?php

namespace App\Providers\ServiceBindingProvider;

use App\Modules\User\Repository\Eloquent\UserRepository;
use App\Modules\User\Repository\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class UserProvider extends ServiceProvider
{

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}