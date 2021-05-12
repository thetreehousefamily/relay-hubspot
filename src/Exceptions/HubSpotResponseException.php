<?php

namespace TheTreehouse\Relay\HubSpot\Exceptions;

use GuzzleHttp\Psr7\Response;

class HubSpotResponseException extends HubSpotRelayException
{
    /**
     * The original response received
     *
     * @var \GuzzleHttp\Psr7\Response
     */
    protected $response;

    /**
     * Instantiate a new exception instance
     *
     * @return void
     */
    public function __construct(string $message, Response $response)
    {
        parent::__construct($message);

        $this->response = $response;
    }

    public static function malformedResponse(Response $response)
    {
        return new static('Failed to parse HubSpot response from endpoint', $response);
    }
}
