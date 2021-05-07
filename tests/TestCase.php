<?php

namespace TheTreehouse\Relay\HubSpot\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use TheTreehouse\Relay\HubSpot\HubSpotRelayServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            HubSpotRelayServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}
