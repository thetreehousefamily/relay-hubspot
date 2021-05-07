<?php

namespace TheTreehouse\Relay\HubSpot\Exceptions;

class HubSpotConfigurationException extends HubSpotRelayException
{
    public static function missingCredentials()
    {
        return new static('Could not load the HubSpot Relay credentials - Please make sure they are defined in `relay.providers.hubspot`');
    }
}
