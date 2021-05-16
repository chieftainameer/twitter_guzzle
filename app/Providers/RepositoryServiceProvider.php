<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\RepositoryInterface;
use App\Repository\UserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(RepositoryInterface::class, UserRepository::class);
    }
}
