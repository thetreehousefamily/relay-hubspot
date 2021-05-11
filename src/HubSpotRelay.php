<?php

namespace TheTreehouse\Relay\HubSpot;

use Illuminate\Database\Eloquent\Model;
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

    /**
     * @inheritdoc
     */
    public function contactCreated(Model $contact)
    {
        $response = $this->hubSpot->call('post', '/contacts', [
            'properties' => [
                'firstname' => $contact->first_name,
                'lastname' => $contact->last_name,
                'email' => $contact->email
            ]
        ]);

        if (!isset($response->getData()['id'])) {
            return;
        }

        $contact->{$this->contactModelColumn()} = $response->getData()['id'];
        $contact->saveQuietly();
    }
}
