<?php

namespace TheTreehouse\Relay\HubSpot\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use TheTreehouse\Relay\HubSpot\HubSpotRelayServiceProvider;
use TheTreehouse\Relay\RelayServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->configureHubSpot();
    }

    protected function getPackageProviders($app)
    {
        return [
            RelayServiceProvider::class,
            HubSpotRelayServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }

    /**
     * Define the generic HubSpot configuration for tests.
     * 
     * @return void
     */
    protected function configureHubSpot()
    {
        config([
            'relay.providers.hubspot' => [
                'apiKey' => 'xxxxxxxxx',
            ]
        ]);
    }
}
