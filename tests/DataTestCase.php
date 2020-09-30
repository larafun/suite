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

        $this->loadMigrationsFrom([
            '--path' => realpath(__DIR__.'/Database/migrations'),
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            \Larafun\Suite\ServiceProvider::class
        ];
    }
}
