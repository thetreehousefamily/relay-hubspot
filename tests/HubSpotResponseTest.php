<?php

namespace TheTreehouse\Relay\HubSpot\Tests;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\StreamInterface;
use TheTreehouse\Relay\HubSpot\Exceptions\HubSpotResponseException;
use TheTreehouse\Relay\HubSpot\HubSpotResponse;

class HubSpotResponseTest extends TestCase
{
    public function test_it_throws_exception_when_response_is_malformed()
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->expects($this->once())
            ->method('getContents')
            ->willReturn('Malformed JSON');

        $response = $this->createMock(Response::class);
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $this->expectException(HubSpotResponseException::class);

        new HubSpotResponse($response);
    }

    public function test_it_returns_correct_data_and_response()
    {
        $expectedData = ['foo' => 'bar'];

        $stream = $this->createMock(StreamInterface::class);
        $stream->expects($this->once())
            ->method('getContents')
            ->willReturn(json_encode($expectedData));

        $response = $this->createMock(Response::class);
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $hubSpotResponse = new HubSpotResponse($response);

        $this->assertEquals(
            $expectedData,
            $hubSpotResponse->getData()
        );

        $this->assertEquals(
            $response,
            $hubSpotResponse->getResponse()
        );
    }
}
