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

        // $this->withFactories(__DIR__.'/database/factories');
        $this->loadMigrationsFrom([
            '--path' => realpath(__DIR__.'/database/migrations'),
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            \Larafun\Suite\ServiceProvider::class
        ];
    }
}
