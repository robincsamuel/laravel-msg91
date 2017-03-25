<?php

namespace RobinCSamuel\LaravelMsg91;

use Illuminate\Support\ServiceProvider;

use Config;

class LaravelMsg91ServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('msg91.php'),
        ]);
    }

    /**
     * Register the service provider.
     * 
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/config.php', 'laravel-msg91'
        );

        $this->app->singleton('laravel-msg91', function ($app) {
            return new LaravelMsg91();
        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('laravel-msg91');
    }
}
