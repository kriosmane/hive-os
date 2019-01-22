<?php

namespace KriosMane\HiveOs\Providers;


use KriosMane\HiveOs\HiveOs;

use Illuminate\Support\ServiceProvider;

class HiveOsServiceProvider extends ServiceProvider
{   

    /*
    * Indicates if loading of the provider is deferred.
    *
    * @var boolean
    */
    protected $defer = true;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {   

        $config = realpath(__DIR__.'/../config/hiveos.php');

        $this->publishes([

            $config => config_path('hiveos.php')

        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('hive-os', function() {

            $access_token = config('hiveos.access_token');

            return new HiveOs($access_token);
            
        });
    }

    /**
    * Get the services provided by the provider
    *
    * @return array
    */
    public function provides()
    {
        return ['hive-os'];
    }
}
