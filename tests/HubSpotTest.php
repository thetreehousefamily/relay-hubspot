<?php

namespace TheTreehouse\Relay\HubSpot\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\StreamInterface;
use TheTreehouse\Relay\HubSpot\Exceptions\HubSpotClientException;
use TheTreehouse\Relay\HubSpot\Exceptions\HubSpotConfigurationException;
use TheTreehouse\Relay\HubSpot\HubSpot;
use TheTreehouse\Relay\HubSpot\HubSpotResponse;

class HubSpotTest extends TestCase
{
    public function test_it_rejects_invalid_configuration()
    {
        config(['relay.providers.hubspot' => null]);

        $this->expectException(HubSpotConfigurationException::class);

        $this->app->make(HubSpot::class);
    }

    public function test_it_builds_correct_api_key_authentication_on_call()
    {
        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->with(
                'post',
                '/foo/path',
                [
                    'query' => [
                        'hapikey' => 'hubspot_api_key'
                    ]
                ]
            )
            ->willReturn($this->mockResponse());

        $hubSpot = new HubSpot('hubspot_api_key', $client, '/foo');

        $hubSpot->call('post', '/path');
    }

    // TODO:
    // public function test_it_builds_correct_oauth_authentication_on_call()
    // {
    //     // ...
    // }

    public function test_it_builds_correct_post_data()
    {
        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->with(
                'post',
                '/foo/path',
                [
                    'query' => [
                        'hapikey' => 'hubspot_api_key'
                    ],
                    'json' => [
                        'foo' => 'bar'
                    ]
                ]
            )
            ->willReturn($this->mockResponse());

        $hubSpot = new HubSpot('hubspot_api_key', $client, '/foo');

        $hubSpot->call('post', '/path', ['foo' => 'bar']);
    }

    public function test_it_throws_client_exception_on_bad_request()
    {
        $response = $this->createMock(Response::class);
        $response->method('getStatusCode')->willReturn(400);

        $clientException = new ClientException(
            'Original Message',
            $this->createMock(Request::class),
            $response
        );

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->withAnyParameters()
            ->willThrowException($clientException);

        $this->expectException(HubSpotClientException::class);
        $this->expectExceptionMessage('A HTTP 400 code was encountered whilst communicating with HubSpot, original message was: Original Message');

        $hubSpot = new HubSpot('hubspot_api_key', $client, '/foo');

        $hubSpot->call('post', '/path');
    }

    public function test_it_returns_correct_hubspot_response()
    {
        $response = $this->mockResponse();
        
        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->withAnyParameters()
            ->willReturn($response);

        $hubSpotResponse = (new HubSpot('hubspot_api_key', $client, '/foo'))
            ->call('post', '/path');

        $this->assertInstanceOf(HubSpotResponse::class, $hubSpotResponse);
        $this->assertSame($response, $hubSpotResponse->getResponse());
        $this->assertEquals(
            [
                'foo' => 'bar'
            ],
            $hubSpotResponse->getData()
        );
    }

    private function mockResponse(array $jsonData = []): Response
    {
        if (!$jsonData) {
            $jsonData = [
                'foo' => 'bar'
            ];
        }

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn(json_encode($jsonData));

        $response = $this->createMock(Response::class);
        $response->method('getBody')->willReturn($stream);

        return $response;
    }
}