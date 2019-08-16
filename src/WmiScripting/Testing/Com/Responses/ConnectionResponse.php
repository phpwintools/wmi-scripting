<?php

namespace PhpWinTools\WmiScripting\Testing\Com\Responses;

use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Testing\Com\Support\VARIANTFake;
use PhpWinTools\WmiScripting\Support\ApiObjects\SWbemLocator;
use PhpWinTools\WmiScripting\Support\ApiObjects\SWbemServices;

class ConnectionResponse extends ApiObjectResponseFactory
{
    public static function standard(Config $config = null)
    {
        $config = $config ?? Config::testInstance();

        static::addVariantResponse(SWbemLocator::class, 'ConnectServer', new VARIANTFake(), $config);
        static::addVariantResponse(SWbemServices::class, 'ExecQuery', new VARIANTFake(), $config);
    }
}
