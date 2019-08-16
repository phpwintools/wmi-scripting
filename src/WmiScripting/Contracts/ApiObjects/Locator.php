<?php

namespace PhpWinTools\WmiScripting\Contracts\ApiObjects;

use PhpWinTools\WmiScripting\Connection;

interface Locator extends WbemObject
{
    public static function connectLocal(): Services;

    public function connectServer(Connection $connection = null): Services;
}
