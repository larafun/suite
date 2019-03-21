<?php

namespace Larafun\Suite;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Larafun\Suite\Collection\ResourceableCollection;

class ServiceProvider extends IlluminateServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/suite.php'   => config_path('suite.php')
        ], 'config');

        Builder::macro('resource', function ($columns = ['*']) {
            $class = config('suite.model.collection', ResourceableCollection::class);
            $collection = new $class($this->get($columns));
            return $collection->setQuery($this);
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\BuildFilterCommand::class,
                Commands\BuildModelCommand::class,
                Commands\BuildResourceCommand::class,
                Commands\BuildControllerCommand::class,
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
