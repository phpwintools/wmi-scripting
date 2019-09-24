<?php

namespace PhpWinTools\WmiScripting\Connections;

use PhpWinTools\WmiScripting\Configuration\Config;

class ConnectionManager
{
    /** @var Config */
    protected $config;

    public function __construct(Config $config = null)
    {
        $this->config = $config ?? Config::instance();
    }
}
