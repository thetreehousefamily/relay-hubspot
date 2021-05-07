<?php

namespace TheTreehouse\Relay\HubSpot\Tests;

use TheTreehouse\Relay\Facades\Relay;
use TheTreehouse\Relay\HubSpot\HubSpotRelay;

class HubSpotRelayTest extends TestCase
{
    public function test_it_registers_with_relay()
    {
        $this->assertContains(HubSpotRelay::class, Relay::getRegisteredProviders());
    }
}
