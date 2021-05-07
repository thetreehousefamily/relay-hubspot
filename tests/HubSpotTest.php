<?php

namespace TheTreehouse\Relay\HubSpot\Tests;

use TheTreehouse\Relay\HubSpot\Exceptions\HubSpotConfigurationException;
use TheTreehouse\Relay\HubSpot\HubSpot;

class HubSpotTest extends TestCase
{
    public function test_it_rejects_invalid_configuration()
    {
        config(['relay.providers.hubspot' => null]);

        $this->expectException(HubSpotConfigurationException::class);

        $this->app->make(HubSpot::class);
    }
}