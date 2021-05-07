<?php

namespace TheTreehouse\Relay\HubSpot\Tests;

use Illuminate\Support\Str;
use TheTreehouse\Relay\HubSpot\Tests\Fixtures\Models\Contact;

class HubSpotRelayE2ETest extends TestCase
{
    /** @var string */
    private $randomId;

    public function setUp(): void
    {
        parent::setUp();

        $this->randomId = Str::random(6);
    }

    public function test_it_creates_contacts()
    {
        $contact = Contact::create([
            'first_name' => 'Josephine',
            'last_name' => 'Smith',
            'email' => $email = "relays_created_contact_{$this->randomId}@example.com"
        ]);

        $this->assertNotNull($hsId = $contact->hubspot_id);

        $this->assertHubSpotContactExists($hsId, [
            'firstname' => 'Josephine',
            'lastname' => 'Smith',
            'email' => $email
        ]);
    }
}
