<?php

namespace TheTreehouse\Relay\HubSpot;

use GuzzleHttp\Psr7\Response;
use TheTreehouse\Relay\HubSpot\Exceptions\HubSpotResponseException;

class HubSpotResponse
{
    /**
     * The decoded JSON data
     * 
     * @var array
     */
    protected $data;

    /**
     * The original response received
     * 
     * @var \GuzzleHttp\Psr7\Response
     */
    protected $response;

    /**
     * Instantiate a new response instance
     * 
     * @var \GuzzleHttp\Psr7\Response $response
     * @return void
     */
    public function __construct(Response $response)
    {
        if (($data = json_decode($response->getBody()->getContents(), true)) === null) {
            throw HubSpotResponseException::malformedResponse($response);
        }

        $this->data = $data;
        $this->response = $response;
    }

    /**
     * Retrieve the decoded json data as an array
     * 
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Retrieve the original response instance
     * 
     * @return \GuzzleHttp\Psr7\Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
}
