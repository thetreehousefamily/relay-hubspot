<?php

namespace TheTreehouse\Relay\HubSpot;

use Illuminate\Support\Facades\Facade;

/**
 * @see \TheTreehouse\Relay\HubSpot\HubSpotRelay
 */
class HubSpotRelayFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'relay-hubspot';
    }
}
