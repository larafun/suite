<?php

namespace Larafun\Suite;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Larafun\Suite\Commands\BuildFilterCommand;

class ServiceProvider extends IlluminateServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/suite.php'   => config_path('suite.php')
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                BuildFilterCommand::class,
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
