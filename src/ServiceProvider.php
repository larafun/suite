<?php

namespace Larafun\Suite;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/suite.php'   => config_path('suite.php')
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\BuildFilterCommand::class,
                Commands\BuildModelCommand::class,
                Commands\BuildResourceCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/suite.php', 'suite'
        );
    }
}
