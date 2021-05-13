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
     * Assert that a contact exists within HubSpot by the given contact id.
     *
     * @param string $contactId
     * @param array $expectedData
     */
    public function assertHubSpotContactExists(string $contactId, array $expectedData = []): self
    {
        return $this->assertHubSpotEntityExists('contact', $contactId, $expectedData);
    }

    /**
     * Assert that a organization exists within HubSpot by the given organization id.
     *
     * @param string $companyId
     * @param array $expectedData
     */
    public function assertHubSpotCompanyExists(string $companyId, array $expectedData = []): self
    {
        return $this->assertHubSpotEntityExists('company', $companyId, $expectedData);
    }

    /**
     * Assert that a HubSpot Contact has been archived
     *
     * @param string $contactId
     */
    public function assertHubSpotContactArchived(string $contactId): self
    {
        return $this->assertHubSpotEntityArchived('contact', $contactId);
    }

    /**
     * Assert that a HubSpot Company has been archived
     *
     * @param string $companyId
     */
    public function assertHubSpotCompanyArchived(string $companyId): self
    {
        return $this->assertHubSpotEntityArchived('company', $companyId);
    }

    /**
     * Assert an entity exists within HubSpot
     */
    private function assertHubSpotEntityExists(string $entityType, string $entityId, array $expectedData = []): self
    {
        $entityTypePlural = $entityType === 'contact'
            ? 'contacts'
            : 'companies';

        try {
            $response = $this->hubSpotApi(
                'get',
                "/objects/{$entityTypePlural}/{$entityId}",
                [],
                [
                    'properties' => implode(',', array_keys($expectedData))
                ]
            );

        } catch (ClientException $exception) {
            $this->fail("Failed to assert HubSpot {$entityType} exists, received: {$exception->getMessage()}");
        }

        if (($jsonResponse = json_decode($response->getBody()->getContents(), true)) === null) {
            $this->fail("Failed to decode returned content from HubSpot API");
        }

        if ($expectedData) {
            $properties = $jsonResponse['properties'];

            foreach ($expectedData as $key => $value) {
                if (! isset($properties[$key])) {
                    $this->fail("HubSpot response missing expected {$entityType} key: {$key}");
                }
                
                $this->assertSame($value, $properties[$key]);
            }
        }

        return $this;
    }

    /**
     * Assert an entity has been archived within HubSpot
     */
    private function assertHubSpotEntityArchived(string $entityType, string $entityId): self
    {
        $entityTypePlural = $entityType === 'contact'
            ? 'contacts'
            : 'companies';
        
        try {
            $response = $this->hubSpotApi('get', "/objects/{$entityTypePlural}/{$entityId}", [], ['archived' => 'true']);
        } catch (ClientException $exception) {
            $this->fail("Failed to assert HubSpot {$entityType} is archived, received: {$exception->getMessage()}");
        }

        if (($jsonResponse = json_decode($response->getBody()->getContents(), true)) === null) {
            $this->fail("Failed to decode returned content from HubSpot API");
        }

        if (! isset($jsonResponse['archived']) || ! $jsonResponse['archived']) {
            $this->fail("Failed to assert that {$entityType}: {$entityId} has been archived");
        }

        return $this;
    }

    /**
     * Execute a request against the HubSpot API
     *
     * @param string $method
     * @return \GuzzleHttp\Psr7\Response
     */
    protected function hubSpotApi(string $method, string $path, array $data = [], array $query = []): Response
    {
        if (! $apiKey = env('HUBSPOT_TEST_API_KEY')) {
            $this->markTestSkipped('Missing HubSpot API Key - Cannot assert against HubSpot.');
        }

        return $this->client()
            ->request(
                $method,
                $this->hubSpotBasePath.$path,
                [
                    'query' => array_merge(
                        [
                            'hapikey' => $apiKey,
                        ],
                        $query
                    ),
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
        if (! $this->client) {
            $this->client = new Client();
        }

        return $this->client;
    }
}
