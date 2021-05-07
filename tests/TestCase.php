<?php

namespace TheTreehouse\Relay\HubSpot\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use TheTreehouse\Relay\HubSpot\HubSpotRelayServiceProvider;
use TheTreehouse\Relay\HubSpot\Tests\Concerns\AssertsAgainstHubSpot;
use TheTreehouse\Relay\HubSpot\Tests\Fixtures\Models\Contact;
use TheTreehouse\Relay\RelayServiceProvider;

class TestCase extends Orchestra
{
    use AssertsAgainstHubSpot;
    
    public function setUp(): void
    {
        parent::setUp();
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
            'CreateContactsTable' => '/Fixtures/Migrations/create_contacts_table.php'
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
                'apiKey' => 'xxxxxxxxx',
            ]
        ]);
    }
}
