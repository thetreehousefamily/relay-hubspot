<?php

namespace TheTreehouse\Relay\HubSpot;

use TheTreehouse\Relay\HubSpot\Exceptions\HubSpotConfigurationException;

class HubSpot
{
    /**
     * The HubSpot API key
     * 
     * @var string|null
     */
    protected $apiKey;

    /**
     * Instantiate a new HubSpot instance
     * 
     * @param mixed $authentication Either an API key string, or TODO: OAuth Object
     * @return void
     */
    public function __construct($authentication)
    {
        if (is_string($authentication) && $authentication) {
            $this->apiKey = $authentication;

            return;
        }

        // TODO: OAuth Implementation

        throw HubSpotConfigurationException::missingCredentials();
    }
}
