<?php

namespace PhpWinTools\WmiScripting\Support\Cache;

use Psr\SimpleCache\CacheInterface;
use PhpWinTools\WmiScripting\Configuration\Config;

abstract class CacheDriver implements CacheInterface
{
    protected $config;

    protected $store;

    public function __construct(Config $config = null)
    {
        $this->config = $config ?? Config::instance();
    }
}
