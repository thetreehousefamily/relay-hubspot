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

    public function test_it_respects_support_settings_from_configuration()
    {
        config(['relay.providers.hubspot.contacts' => false]);
        config(['relay.providers.hubspot.organizations' => false]);

        $relay = $this->newHubSpotRelay();

        $this->assertFalse($relay->supportsContacts());
        $this->assertFalse($relay->supportsOrganizations());
    }

    private function newHubSpotRelay(): HubSpotRelay
    {
        return $this->app->make(HubSpotRelay::class);
    }
}
