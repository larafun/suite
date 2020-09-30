<?php

namespace Larafun\Suite\Tests;

class DataTestCase extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    protected function getPackageProviders($app)
    {
        return [
            \Larafun\Suite\ServiceProvider::class
        ];
    }
}
