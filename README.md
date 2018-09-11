# Correios zip code query with PHP

A service for fetching data from an address from a provided zip code.

## Installation

``` bash
$ composer require blancohugo/correios
```

## How-to

See the example below of how to implement a search for address data:

``` php
<?php

require 'vendor/autoload.php';

use BlancoHugo\Correios\Zipcode;
use BlancoHugo\Correios\Client;
use BlancoHugo\Correios\Exception;

$client = new Client();
$address = $client->query(new Zipcode('01311-929'));

```

The client class uses exceptions for data treatments. Make sure your code will have a treatment for the following situations:

``` php
<?php

try {
    $address = $client->query(new Zipcode('01311-929'));
} catch (Exception\InvalidZipcodeException $e) {
    // Invalid zipcode
} catch (Exception\ZipcodeNotFoundException $e) {
    // Address data not found
} catch (Exception\UnexpectedResponseException $e) {
    // Unexpected response from SIGEP
}

```

## Contributing

See how to [CONTRIBUTE](https://github.com/blancohugo/correios/blob/master/CONTRIBUTING.md) to this project.