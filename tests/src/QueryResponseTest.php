<?php

namespace BlancoHugo\Correios;

use \SimpleXmlElement;
use PHPUnit\Framework\TestCase;

class QueryResponseTest extends TestCase
{
    private function getXmlObject(string $filePath = null): SimpleXmlElement
    {
        if (!$filePath) {
            return new SimpleXmlElement('<?xml version="1.0" encoding="UTF-8"?><Body></Body>');
        }

        $xml = file_get_contents($filePath);
        $xml = str_replace(['ns2:', 'soap:'], null, $xml);

        return simplexml_load_string($xml);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $queryResponse = new QueryResponse();
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructedFromSigepResponse()
    {
        $queryResponse = QueryResponse::fromSigepResponse($this->getXmlObject());
    }

    /**
     * @test
     */
    public function objectCanBeConstructedFromEmptyResponse()
    {
        $queryResponse = QueryResponse::fromEmptyResponse();
        $this->assertEquals([], $queryResponse->getData());
    }

    /**
     * @test
     */
    public function objectWithSuccessfulDataShouldHaveData()
    {
        $xml = $this->getXmlObject(__DIR__ . '/../data/validZipcode.xml');
        $queryResponse = QueryResponse::fromSigepResponse($xml);

        $this->assertNotEmpty($queryResponse->getData());
    }

    /**
     * @test
     */
    public function objectWithFailureDataShouldHaveErrors()
    {
        $xml = $this->getXmlObject(__DIR__ . '/../data/invalidZipcode.xml');
        $queryResponse = QueryResponse::fromSigepResponse($xml);

        $this->assertTrue($queryResponse->hasErrors());
        $this->assertTrue($queryResponse->hasNotFoundError());
    }
}
