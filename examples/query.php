<?php

require '../vendor/autoload.php';

use BlancoHugo\Correios\Zipcode;
use BlancoHugo\Correios\Client;
use BlancoHugo\Correios\Exception;

$client = new Client();

try {
    $address = $client->query(new Zipcode('01311-929'));
} catch (Exception\InvalidZipcodeException $e) {
    die('Invalid zipcode');
} catch (Exception\ZipcodeNotFoundException $e) {
    die('Address data not found');
} catch (Exception\UnexpectedResponseException $e) {
    die('Unexpected response from sigep, try again later');
}

var_dump($address);
