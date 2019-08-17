<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects\Contracts;

use PhpWinTools\WmiScripting\Connection;

interface Locator extends WbemObject
{
    public function connectServer(Connection $connection = null): Services;
}
