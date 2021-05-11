<?php

namespace TheTreehouse\Relay\HubSpot\Tests\Concerns;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;

trait AssertsAgainstHubSpot
{
    /**
     * Defines the base URL for test HubSpot API requests
     */
    public $hubSpotBasePath = 'https://api.hubapi.com/crm/v3';

    /**
     * The Guzzle Client instnace
     * @param \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Assert that a contact exists within HubSpot by the given contact id. Optionally, assert
     * that the matching contact is configured with the provided expected data
     * 
     * @param string $contactId The numeric HubSpot ID of the contact
     * @param array $expectedData Optional: Assert that the matching contact is configured with the expected property data
     */
    public function assertHubSpotContactExists(string $contactId, array $expectedData = []): self
    {
        try {
            $response = $this->hubSpotApi('get', "/objects/contacts/{$contactId}");
        } catch (ClientException $exception) {
            $this->fail("Failed to assert HubSpot contact exists, received: {$exception->getMessage()}");
        }

        if (($jsonResponse = json_decode($response->getBody()->getContents(), true)) === null) {
            $this->fail("Failed to decode returned content from HubSpot API");
        }

        if ($expectedData) {
            $properties = $jsonResponse['properties'];

            foreach ($expectedData as $key => $value) {
                $this->assertSame($value, $properties[$key]);
            }
        }

        return $this;
    }

    /**
     * Execute a request against the HubSpot API
     * 
     * @param string $method
     * @return \GuzzleHttp\Psr7\Response 
     */
    protected function hubSpotApi(string $method, string $path, array $data = []): Response
    {
        if (!$apiKey = env('HUBSPOT_TEST_API_KEY')) {
            $this->markTestSkipped('Missing HubSpot API Key - Cannot assert against HubSpot.');
        }

        return $this->client()
            ->request(
                $method,
                $this->hubSpotBasePath.$path,
                [
                    'query' => [
                        'hapikey' => $apiKey
                    ]
                ]
            );
    }

    /**
     * Return a Guzzle Client instance
     * 
     * @return \GuzzleHttp\Client
     */
    protected function client(): Client
    {
        if (!$this->client) {
            $this->client = new Client();
        }

        return $this->client;
    }
}