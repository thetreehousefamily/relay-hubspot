<?php

namespace TheTreehouse\Relay\HubSpot\Tests;

use TheTreehouse\Relay\Facades\Relay;
use TheTreehouse\Relay\HubSpot\Exceptions\HubSpotConfigurationException;
use TheTreehouse\Relay\HubSpot\HubSpotRelay;

class HubSpotRelayTest extends TestCase
{
    public function test_it_registers_with_relay()
    {
        $this->assertContains(HubSpotRelay::class, Relay::getRegisteredProviders());
    }

    public function test_it_rejects_invalid_configuration()
    {
        config(['relay.providers.hubspot' => null]);

        $this->expectException(HubSpotConfigurationException::class);

        $this->app->make(HubSpotRelay::class);
    }
}
