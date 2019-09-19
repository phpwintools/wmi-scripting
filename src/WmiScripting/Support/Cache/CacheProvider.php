<?php

namespace PhpWinTools\WmiScripting\Support\Cache;

use Psr\SimpleCache\CacheInterface;
use PhpWinTools\WmiScripting\Configuration\Config;

class CacheProvider implements CacheInterface
{
    protected $config;

    /** @var CacheDriver */
    protected $cacheDriver;

    public function __construct(Config $config = null, CacheDriver $cacheDriver = null)
    {
        $this->config = $config ?? Config::instance();

        $this->cacheDriver = $cacheDriver ?? $this->config->getCacheDriver();
    }

    public function get($key, $default = null)
    {
        return $this->cacheDriver->get($key, $default);
    }

    public function set($key, $value, $ttl = null)
    {
        return $this->cacheDriver->set($key, $value, $ttl);
    }

    public function delete($key)
    {
        return $this->cacheDriver->delete($key);
    }

    public function clear()
    {
        return $this->cacheDriver->clear();
    }

    public function getMultiple($keys, $default = null)
    {
        return $this->cacheDriver->getMultiple($keys, $default);
    }

    public function setMultiple($values, $ttl = null)
    {
        return $this->cacheDriver->setMultiple($values, $ttl);
    }

    public function deleteMultiple($keys)
    {
        return $this->cacheDriver->deleteMultiple($keys);
    }

    public function has($key)
    {
        return $this->cacheDriver->has($key);
    }
}
