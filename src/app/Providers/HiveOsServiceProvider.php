<?php

namespace KriosMane\HiveOs\app\Providers;


use KriosMane\HiveOs\app\HiveOs;

use Illuminate\Support\ServiceProvider;

class HiveOsServiceProvider extends ServiceProvider
{   

    /*
    * Indicates if loading of the provider is deferred.
    *
    * @var bool
    */
    protected $defer = false;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {   

        $config = realpath(__DIR__.'/../../config/hiveos.php');

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

        $this->app->bind('hive-os', function() {

            return new HiveOs;
            
        });
    }

    /**
    * Get the services provided by the provider
    * @return array
    */
    public function provides()
    {
        return ['hive-os'];
    }
}
