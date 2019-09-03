<?php

namespace PhpWinTools\WmiScripting\Support\Events;

use PhpWinTools\WmiScripting\Configuration\Config;

class Bus
{
    /** @var Config|null */
    protected $config;

    protected $registeredEvents;

    public function __construct(Config $config = null)
    {
        $this->config = $config ?? Config::instance();
    }
}
