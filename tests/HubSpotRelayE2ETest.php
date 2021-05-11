<?php

namespace TheTreehouse\Relay\HubSpot\Tests;

use Illuminate\Support\Str;
use TheTreehouse\Relay\HubSpot\Tests\Fixtures\Models\Contact;
use TheTreehouse\Relay\HubSpot\Tests\Fixtures\Models\Organization;

class HubSpotRelayE2ETest extends TestCase
{
    /** @var string */
    private $randomId;

    public function setUp(): void
    {
        parent::setUp();

        $this->randomId = strtolower(Str::random(6));
    }

    public function test_it_creates_contacts()
    {
        $contact = Contact::create([
            'first_name' => 'Josephine',
            'last_name' => 'Smith',
            'email' => $email = "relays_created_contact_{$this->randomId}@example.com",
        ]);

        $this->assertNotNull($hsId = $contact->hubspot_id);

        $this->assertHubSpotContactExists($hsId, [
            'firstname' => 'Josephine',
            'lastname' => 'Smith',
            'email' => $email,
        ]);
    }

    public function test_it_creates_organizations()
    {
        $organization = Organization::create([
            'name' => $name = "Example Organization: {$this->randomId}",
        ]);

        $this->assertNotNull($hsId = $organization->hubspot_id);

        $this->assertHubSpotCompanyExists($hsId, [
            'name' => $name,
        ]);
    }
}
