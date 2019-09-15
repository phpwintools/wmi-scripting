<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects\Contracts;

use PhpWinTools\WmiScripting\Connections\ComConnection;

interface Locator extends WbemObject
{
    public function connectServer(ComConnection $connection = null): Services;
}
