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
     * @inheritdoc
     */
    protected $configKey = 'relay.providers.hubspot';

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
    public function contactCreated(Model $contact, array $outboundProperties)
    {
        $response = $this->hubSpot->call('post', '/contacts', [
            'properties' => $outboundProperties
        ]);

        if (! isset($response->getData()['id'])) {
            return;
        }

        $contact->{$this->contactModelColumn()} = $response->getData()['id'];
        $contact->saveQuietly();
    }

    /**
     * @inheritdoc
     */
    public function organizationCreated(Model $organization, array $outboundProperties)
    {
        $response = $this->hubSpot->call('post', '/companies', [
            'properties' => $outboundProperties
        ]);

        if (! isset($response->getData()['id'])) {
            return;
        }

        $organization->{$this->organizationModelColumn()} = $response->getData()['id'];
        $organization->saveQuietly();
    }

    /**
     * @inheritdoc
     */
    public function contactUpdated(Model $contact, array $outboundProperties)
    {
        $contactId = $contact->{$this->contactModelColumn()};

        $this->hubSpot->call('patch', "/contacts/{$contactId}", [
            'properties' => $outboundProperties
        ]);
    }

    /**
     * @inheritdoc
     */
    public function organizationUpdated(Model $organization, array $outboundProperties)
    {
        $companyId = $organization->{$this->organizationModelColumn()};

        $this->hubSpot->call('patch', "/companies/{$companyId}", [
            'properties' => $outboundProperties
        ]);
    }
}
