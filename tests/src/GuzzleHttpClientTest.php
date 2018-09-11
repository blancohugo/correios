<?php

namespace BlancoHugo\Correios;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;

class GuzzleHttpClientTest extends TestCase
{
    private function getMockedHttpClient(string $responseFilePath = null)
    {
        $mockHandler = new MockHandler();

        if ($responseFilePath) {
            $mockHandler->append(
                new Response(200, [], file_get_contents($responseFilePath))
            );
        }

        return new Client([
            'handler' => HandlerStack::create($mockHandler),
        ]);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $client = new GuzzleHttpClient($this->getMockedHttpClient());
    }

    /**
     * @test
     */
    public function validQueryShouldReturnData()
    {
        $httpClient = $this->getMockedHttpClient(__DIR__ . '/../data/validZipcode.xml');
        $client = new GuzzleHttpClient($httpClient);

        $response = $client->query(new Zipcode('12345-678'));

        $this->assertNotEmpty($response->getData());
        $this->assertFalse($response->hasErrors());
    }

    /**
     * @test
     */
    public function invalidQueryShouldNotReturnData()
    {
        $httpClient = $this->getMockedHttpClient(__DIR__ . '/../data/invalidZipcode.xml');
        $client = new GuzzleHttpClient($httpClient);

        $response = $client->query(new Zipcode('12345-678'));

        $this->assertEmpty($response->getData());
        $this->assertTrue($response->hasErrors());
    }

    /**
     * @test
     */
    public function emptyQueryResponseShouldHaveErrors()
    {
        $httpClient = $this->getMockedHttpClient(__DIR__ . '/../data/emptyResponse.xml');
        $client = new GuzzleHttpClient($httpClient);

        $response = $client->query(new Zipcode('12345-678'));

        $this->assertEmpty($response->getData());
        $this->assertTrue($response->hasErrors());
    }
}
