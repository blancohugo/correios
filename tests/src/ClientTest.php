<?php

namespace BlancoHugo\Correios;

use PHPUnit\Framework\TestCase;
use BlancoHugo\Correios\Exception\InvalidZipcodeException;
use BlancoHugo\Correios\Exception\ZipcodeNotFoundException;

class ClientTest extends TestCase
{
    private function getMockedQueryResponse(bool $hasNotFoundError = false, bool $hasError = false)
    {
        $httpClient = $this->getMockBuilder(QueryResponse::class)
            ->setMethods([
                'hasNotFoundError',
                'hasErrors',
                'getErrors'
            ])
            ->getMock();

        $httpClient->method('hasNotFoundError')->will($this->returnValue($hasNotFoundError));
        $httpClient->method('hasErrors')->will($this->returnValue($hasError));
        $httpClient->method('getErrors')->will($this->returnValue([]));

        return $httpClient;
    }

    private function getMockedHttpClient(bool $hasNotFoundError = false, bool $hasError = false)
    {
        $httpClient = $this->getMockBuilder(HttpClient::class)
            ->setMethods(['query'])
            ->getMock();

        $queryResponse = $this->getMockedQueryResponse($hasNotFoundError, $hasError);
        $httpClient->method('query')->will($this->returnValue($queryResponse));

        return $httpClient;
    }

    private function getMockedZipcode($isValid = true)
    {
        $zipcode = $this->getMockBuilder(Zipcode::class)
            ->disableOriginalConstructor()
            ->setMethods(['isValid', '__toString'])
            ->getMock();

        $zipcode->method('isValid')->will($this->returnValue($isValid));
        return $zipcode;
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructedWithoutHttpClient()
    {
        $client = new Client();
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructedWithHttpClient()
    {
        $client = new Client($this->getMockedHttpClient());
    }

    /**
     * @test
     * @depends objectCanBeConstructedWithHttpClient
     * @expectedException BlancoHugo\Correios\Exception\InvalidZipcodeException
     */
    public function queryWithInvalidZipcodeShouldThrowException()
    {
        $client = new Client($this->getMockedHttpClient());
        $zipcode = $this->getMockedZipcode($isValid = false);

        $client->query($zipcode);
    }

    /**
     * @test
     * @depends objectCanBeConstructedWithHttpClient
     * @expectedException BlancoHugo\Correios\Exception\ZipcodeNotFoundException
     */
    public function queryResponseWithNotFoundErrorShouldThrowException()
    {
        $client = new Client($this->getMockedHttpClient($hasNotFoundError = true));
        $zipcode = $this->getMockedZipcode($isValid = true);

        $client->query($zipcode);
    }

    /**
     * @test
     * @depends objectCanBeConstructedWithHttpClient
     * @expectedException BlancoHugo\Correios\Exception\UnexpectedResponseException
     */
    public function queryResponseWithUnexpectedErrorShouldThrowException()
    {
        $client = new Client($this->getMockedHttpClient($hasNotFoundError = false, $hasError = true));
        $zipcode = $this->getMockedZipcode($isValid = true);

        $client->query($zipcode);
    }

    /**
     * @test
     * @depends objectCanBeConstructedWithHttpClient
     */
    public function validQueryShouldReturnAddress()
    {
        $client = new Client($this->getMockedHttpClient());
        $zipcode = $this->getMockedZipcode($isValid = true);

        $address = $client->query($zipcode);

        $this->assertInstanceOf(Address::class, $address);
    }
}
