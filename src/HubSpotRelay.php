<?php

namespace TheTreehouse\Relay\HubSpot;

use TheTreehouse\Relay\AbstractProvider;

class HubSpotRelay extends AbstractProvider
{
    /**
     * The HubSpot instance for interacting with the API
     * 
     * @var \TheTreehouse\Relay\HubSpot\HubSpot
     */
    protected $hubSpot;

    /**
     * Instantiate the HubSpotRelay singleton
     * 
     * @param \TheTreehouse\Relay\HubSpot\HubSpot $hubSpot
     * @return void
     */
    public function __construct(HubSpot $hubSpot)
    {
        $this->hubSpot = $hubSpot;

        $this->supportsContacts = config('relay.providers.hubspot.contacts', true);
        $this->supportsOrganizations = config('relay.providers.hubspot.organizations', true);

        $this->contactModelColumn = config('relay.providers.hubspot.contact_model_column', 'hubspot_id');
        $this->organizationModelColumn = config('relay.providers.hubspot.organization_model_column', 'hubspot_id');
    }
}
