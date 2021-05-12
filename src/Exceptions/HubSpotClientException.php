<?php

namespace TheTreehouse\Relay\HubSpot\Exceptions;

use Throwable;

class HubSpotClientException extends HubSpotRelayException
{
    public static function create(string $message, int $httpStatus, Throwable $previous)
    {
        return new static(
            "A HTTP {$httpStatus} code was encountered whilst communicating with HubSpot, original message was: {$message}",
            0,
            $previous
        );
    }
}
