<?php

namespace App\Providers;

use App\Repositories\Contracts\MasterIdentityRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\MasterIdentityRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(MasterIdentityRepositoryInterface::class, MasterIdentityRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
