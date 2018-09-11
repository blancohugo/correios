<?php

namespace BlancoHugo\Correios;

/**
 * Generic http client
 */
abstract class HttpClient
{
    /**
     * SIGEP query url
     */
    const URL = 'https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente';

    /**
     * Generates xml for request body
     *
     * @param Zipcode $zipcode
     * @return string
     */
    protected function generateRequestXml(Zipcode $zipcode): string
    {
        $formattedZipcode = preg_replace('/[^0-9]/', null, (string) $zipcode);

        return sprintf(
            '<?xml version="1.0"?>
            <soapenv:Envelope
                xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
                xmlns:cli="http://cliente.bean.master.sigep.bsb.correios.com.br/">
                <soapenv:Header/>
                <soapenv:Body>
                    <cli:consultaCEP>
                        <cep>%s</cep>
                    </cli:consultaCEP>
                </soapenv:Body>
            </soapenv:Envelope>',
            $formattedZipcode
        );
    }

    /**
     * Query the address data by zipcode
     *
     * @param Zipcode $zipcode
     * @return QueryResponse
     */
    abstract public function query(Zipcode $zipcode): QueryResponse;
}
