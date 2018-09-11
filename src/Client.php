<?php

namespace BlancoHugo\Correios;

/**
 * Zipcode client
 */
class Client
{
    /**
     * Http client
     *
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * Constructor
     *
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new GuzzleHttpClient();
    }

    /**
     * Check the provided zipcode
     *
     * @param Zipcode $zipcode
     * @return Address
     * @throws Exception\InvalidZipcodeException
     * @throws Exception\ZipcodeNotFoundException
     * @throws Exception\UnexpectedResponseException
     */
    public function query(Zipcode $zipcode): Address
    {
        if (!$zipcode->isValid()) {
            throw new Exception\InvalidZipcodeException(
                sprintf('"%s" is not a valid zipcode', (string) $zipcode)
            );
        }

        $response = $this->httpClient->query($zipcode);

        if ($response->hasNotFoundError()) {
            throw new Exception\ZipcodeNotFoundException(
                sprintf('The zipcode "%s" does not match any addresses', (string) $zipcode)
            );
        } elseif ($response->hasErrors()) {
            throw new Exception\UnexpectedResponseException(
                sprintf('The address data could not be retrieved: %s', implode(', ', $response->getErrors()))
            );
        }

        return Address::fromSigepData($zipcode, $response->getData());
    }
}
