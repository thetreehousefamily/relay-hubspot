<?php

namespace TheTreehouse\Relay\HubSpot\Tests;

use Carbon\Carbon;
use Illuminate\Support\Str;
use TheTreehouse\Relay\HubSpot\Tests\Contracts\TestsAgainstHubSpot;
use TheTreehouse\Relay\HubSpot\Tests\Fixtures\Models\Contact;
use TheTreehouse\Relay\HubSpot\Tests\Fixtures\Models\Organization;

class HubSpotRelayE2ETest extends TestCase implements TestsAgainstHubSpot
{
    /** @group focus */
    public function test_it_creates_contacts()
    {
        $contact = Contact::create([
            'first_name' => 'Josephine',
            'last_name' => 'Smith',
            'email' => $email = $this->randomEmail(),
            'hs_custom_property_date' => ($date = Carbon::now())->toDateString()
        ]);

        $this->assertNotNull($hsId = $contact->hubspot_id);

        $this->assertHubSpotContactExists($hsId, [
            'firstname' => 'Josephine',
            'lastname' => 'Smith',
            'email' => $email,
            'custom_property_date' => $date->toDateString()
        ]);
    }

    public function test_it_creates_organizations()
    {
        $organization = Organization::create([
            'name' => $name = $this->randomName(),
        ]);

        $this->assertNotNull($hsId = $organization->hubspot_id);

        $this->assertHubSpotCompanyExists($hsId, [
            'name' => $name,
        ]);
    }

    public function test_it_updates_contacts()
    {
        /** @var \TheTreehouse\Relay\HubSpot\Tests\Fixtures\Models\Contact $contact */
        $contact = Contact::create([
            'first_name' => 'Josephine',
            'last_name' => 'Smith',
            'email' => $this->randomEmail(),
        ]);

        $this->assertNotNull($hsId = $contact->hubspot_id);

        $contact->fill([
            'first_name' => 'Josie',
            'last_name' => 'Smithe',
            'email' => $email = $this->randomEmail(),
        ]);

        $contact->save();

        $this->assertHubSpotContactExists($hsId, [
            'firstname' => 'Josie',
            'lastname' => 'Smithe',
            'email' => $email,
        ]);
    }

    public function test_it_updates_organizations()
    {
        /** @var \TheTreehouse\Relay\HubSpot\Tests\Fixtures\Models\Organization $organization */
        $organization = Organization::create([
            'name' => $this->randomName(),
        ]);

        $this->assertNotNull($hsId = $organization->hubspot_id);

        $organization->fill([
            'name' => $name = $this->randomName(),
        ]);

        $organization->save();

        $this->assertHubSpotCompanyExists($hsId, [
            'name' => $name,
        ]);
    }

    public function test_it_deletes_contacts()
    {
        /** @var \TheTreehouse\Relay\HubSpot\Tests\Fixtures\Models\Contact $contact */
        $contact = Contact::create([
            'first_name' => 'Josephine',
            'last_name' => 'Smith',
            'email' => $this->randomEmail(),
        ]);

        $this->assertNotNull($hsId = $contact->hubspot_id);

        $contact->delete();

        $this->assertHubSpotContactArchived($hsId);
    }

    public function test_it_deletes_organizations()
    {
        /** @var \TheTreehouse\Relay\HubSpot\Tests\Fixtures\Models\Organization $organization */
        $organization = Organization::create([
            'name' => $this->randomName(),
        ]);

        $this->assertNotNull($hsId = $organization->hubspot_id);

        $organization->delete();

        $this->assertHubSpotCompanyArchived($hsId);
    }

    /**
     * Generate a random email
     *
     * @return string
     */
    private function randomEmail($test = null): string
    {
        return (string) Str::of($test ?? $this->formattedTestName())
            ->snake()
            ->append(
                '_',
                Str::random(6),
                '@example.org'
            )
            ->lower();
    }

    /**
     * Generate a random organization name
     *
     * @return string
     */
    private function randomName($test = null): string
    {
        return (string) Str::of($test ?? $this->formattedTestName())
            ->title()
            ->append(
                ' - ',
                Str::random(6),
            );
    }
}
