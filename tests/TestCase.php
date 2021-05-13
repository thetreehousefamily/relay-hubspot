<?php

namespace TheTreehouse\Relay\HubSpot\Tests;

use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase as Orchestra;
use TheTreehouse\Relay\HubSpot\HubSpotRelayServiceProvider;
use TheTreehouse\Relay\HubSpot\Tests\Concerns\AssertsAgainstHubSpot;
use TheTreehouse\Relay\HubSpot\Tests\Contracts\TestsAgainstHubSpot;
use TheTreehouse\Relay\HubSpot\Tests\Fixtures\Models\Contact;
use TheTreehouse\Relay\HubSpot\Tests\Fixtures\Models\Organization;
use TheTreehouse\Relay\RelayServiceProvider;

class TestCase extends Orchestra
{
    use AssertsAgainstHubSpot;
    
    public function setUp(): void
    {
        parent::setUp();

        if ($this instanceof TestsAgainstHubSpot) {
            sleep(1);
        }
    }

    protected function getPackageProviders($app)
    {
        return [
            RelayServiceProvider::class,
            HubSpotRelayServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $this->configureRelay();
        
        $this->configureHubSpot();

        $this->runFixtureMigrations();
    }

    /**
     * Run the fixture migrations
     *
     * @return void
     */
    protected function runFixtureMigrations()
    {
        $migrations = [
            'CreateContactsTable' => '/Fixtures/Migrations/create_contacts_table.php',
            'CreateOrganizationsTable' => '/Fixtures/Migrations/create_organizations_table.php',
        ];

        foreach ($migrations as $class => $file) {
            include_once __DIR__.$file;
            (new $class)->up();
        }
    }

    /**
     * Define the generic Relay configuration for tests.
     *
     * @return void
     */
    protected function configureRelay()
    {
        config(['relay.contact' => Contact::class]);
        config(['relay.organization' => Organization::class]);
    }

    /**
     * Define the generic HubSpot configuration for tests.
     *
     * @return void
     */
    protected function configureHubSpot()
    {
        config([
            'relay.providers.hubspot' => [
                'apiKey' => env('HUBSPOT_TEST_API_KEY', ''),

                'contact_fields' => [
                    'first_name' => 'firstname',
                    'last_name' => 'lastname',
                    'email' => 'email',
                    'hs_custom_property_date' => 'custom_property_date::date',
                ],

                'organization_fields' => [
                    'name' => 'name',
                    'hs_custom_property_date' => 'custom_property_date::date',
                ],
            ],
        ]);
    }

    /**
     * Retrieve the current test's formatted name
     *
     * @return string
     */
    protected function formattedTestName(): string
    {
        return (string) Str::of($this->getName())
            ->replace('test_', '')
            ->replace('_', ' ')
            ->title();
    }
}
