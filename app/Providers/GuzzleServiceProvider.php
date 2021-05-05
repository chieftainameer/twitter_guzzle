<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GuzzleConnection;

class GuzzleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(GuzzleConnection::class,function(){
            return new GuzzleConnection;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
