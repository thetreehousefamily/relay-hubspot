<?php

namespace TheTreehouse\Relay\HubSpot;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use TheTreehouse\Relay\HubSpot\Exceptions\HubSpotClientException;
use TheTreehouse\Relay\HubSpot\Exceptions\HubSpotConfigurationException;

class HubSpot
{
    /**
     * The HubSpot API key
     *
     * @var string|null
     */
    protected $apiKey;

    /**
     * The Guzzle Client instance, for making outbound HTTP requests
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * The base HTTP path for making requests against the HubSpot API
     *
     * @var string
     */
    protected $basePath;

    /**
     * Instantiate a new HubSpot instance
     *
     * @param mixed $authentication Either an API key string, or TODO: OAuth Object
     * @param \GuzzleHttp\Client $client
     * @param string $basePath
     * @return void
     */
    public function __construct($authentication, Client $client, string $basePath)
    {
        $this->client = $client;
        $this->basePath = $basePath;

        if (is_string($authentication) && $authentication) {
            $this->apiKey = $authentication;

            return;
        }

        // TODO: OAuth Implementation

        throw HubSpotConfigurationException::missingCredentials();
    }

    /**
     * Call a HubSpot API Endpoint
     *
     * @param string $method
     * @param string $path
     * @param array $data
     * @return \TheTreehouse\Relay\HubSpot\HubSpotResponse
     */
    public function call(string $method, string $path, array $data = []): HubSpotResponse
    {
        $options = $this->initialiseOptions();

        if ($method === 'post' && $data) {
            $options['json'] = $data;
        }

        try {
            $response = $this->client->request(
                $method,
                $this->basePath.$path,
                $options
            );
        } catch (ClientException $exception) {
            throw HubSpotClientException::create(
                $exception->getMessage(),
                $exception->getResponse()->getStatusCode(),
                $exception
            );
        }

        return new HubSpotResponse($response);
    }

    /**
     * Generate the initial Guzzle options array, based on the configured authentication method
     *
     * @return array
     */
    private function initialiseOptions(): array
    {
        $options = [
            'query' => [],
        ];

        if ($this->apiKey) {
            $options['query']['hapikey'] = $this->apiKey;
        }

        return $options;
    }
}
