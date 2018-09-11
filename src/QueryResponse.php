<?php

namespace BlancoHugo\Correios;

use \SimpleXmlElement;

/**
 * Zipcode query response
 */
class QueryResponse
{
    /**
     * Query data from response
     *
     * @var array
     */
    protected $data;

    /**
     * Errors list
     *
     * @var array
     */
    protected $errors;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data = [];
        $this->errors = [];
    }

    /**
     * Creates an instance from SIGEP xml response
     *
     * @param SimpleXmlElement $xml
     * @return self
     */
    public static function fromSigepResponse(SimpleXmlElement $xml): self
    {
        $parsedXml = json_decode(json_encode($xml->Body), true);
        $queryResponse = new QueryResponse();

        if (array_key_exists('Fault', $parsedXml)) {
            return $queryResponse->addError($parsedXml['Fault']['faultstring']);
        }

        $sigepResponse = $parsedXml['consultaCEPResponse'] ?? [];
        $data = $sigepResponse['return'] ?? [];

        return $queryResponse->setData($data);
    }

    /**
     * Creates an instance from an empty response
     *
     * @return self
     */
    public static function fromEmptyResponse(): self
    {
        return (new QueryResponse())->addError('Empty or unreadable response received');
    }

    /**
     * Sets response data
     *
     * @param array $data
     * @return self
     */
    protected function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Returns the response data
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Adds an error to the list
     *
     * @param string $error
     * @return self
     */
    protected function addError(string $error): self
    {
        $this->errors[] = $error;
        return $this;
    }

    /**
     * Checks if there is any "Zipcode Not Found" error
     *
     * @return boolean
     */
    public function hasNotFoundError(): bool
    {
        return in_array('CEP NAO ENCONTRADO', $this->errors);
    }

    /**
     * Checks for errors
     *
     * @return boolean
     */
    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }
}
