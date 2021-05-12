<?php

namespace TheTreehouse\Relay\HubSpot\Exceptions;

use TheTreehouse\Relay\Exceptions\RelayException;

class HubSpotRelayException extends RelayException
{
    public static function dependentServiceNotLoaded()
    {
        return new static(
            'HubSpot Relay cannot be booted because the parent Relay service provider was not loaded. '
            .'If not using auto-discovery, please ensure the Relay Service Provided is added to your `providers` configuration array.'
        );
    }
}
