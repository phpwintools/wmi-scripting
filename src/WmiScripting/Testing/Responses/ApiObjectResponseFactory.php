<?php

namespace PhpWinTools\WmiScripting\Testing\Responses;

use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Testing\Support\VARIANTFake;

class ApiObjectResponseFactory
{
    public static function add($api_object, $method, $response, Config $config = null)
    {
        $config = $config ?? Config::testInstance();

        $config->addResolvable("{$api_object}.{$method}", $response);
    }

    public static function addVariantResponse($api_object, $method, $response, Config $config = null)
    {
        $config->addResolvable("{$api_object}.{$method}", VARIANTFake::withResponse($method, $response));
    }
}
