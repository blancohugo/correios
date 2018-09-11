<?php

namespace BlancoHugo\Correios;

use \SimpleXmlElement;
use Psr\Http\Message\ResponseInterface;

/**
 * Http client with GuzzleHttp
 */
class GuzzleHttpClient extends HttpClient
{
    /**
     * Guzzle http client
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * Constructor
     *
     * @param \GuzzleHttp\ClientInterface $httpClient
     */
    public function __construct(\GuzzleHttp\ClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new \GuzzleHttp\Client();
    }

    /**
     * {@inheritDoc}
     */
    public function query(Zipcode $zipcode): QueryResponse
    {
        $response = $this->doRequest($this->generateRequestXml($zipcode));
        $parsedResponse = $this->parseResponse($response);

        if (!$parsedResponse) {
            return QueryResponse::fromEmptyResponse();
        }

        return QueryResponse::fromSigepResponse($parsedResponse);
    }

    /**
     * Executes the request
     *
     * @param string $body
     * @return ResponseInterface
     */
    private function doRequest(string $body): ResponseInterface
    {
        return $this->httpClient->post(self::URL, [
            'http_errors' => false,
            'body' => $body,
            'headers' => [
                'Content-Type' => 'application/xml; charset=utf-8',
                'Cache-Control' => 'no-cache'
            ]
        ]);
    }

    /**
     * Converts the string response to xml
     *
     * @param ResponseInterface $response
     * @return SimpleXmlElement|null
     */
    private function parseResponse(ResponseInterface $response): ?SimpleXmlElement
    {
        $xml = $response->getBody()->getContents();

        if (!$xml) {
            return null;
        }

        // Remove namespaces that prevent xml from reading
        $xml = str_replace(['ns2:', 'soap:'], null, $xml);

        return simplexml_load_string($xml);
    }
}
