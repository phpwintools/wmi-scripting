<?php

namespace PhpWinTools\WmiScripting\Support\Cache;

use Psr\SimpleCache\CacheInterface;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Exceptions\CacheInvalidArgumentException;

abstract class CacheDriver implements CacheInterface
{
    protected $config;

    protected $store;

    public function __construct(Config $config = null)
    {
        $this->config = $config ?? Config::instance();
    }

    /**
     * @param string $key
     *
     * @return bool
     *
     * @throws CacheInvalidArgumentException
     */
    abstract public function has($key);


    public function expired($key, $ttl = null): bool
    {
        return false;
    }

    public function valid($key, $ttl = null)
    {
        return $this->expired($key, $ttl) === false;
    }

    public function exists($key)
    {
        return $this->has($key);
    }

    public function doesNotExist($key)
    {
        return $this->exists($key) === false;
    }

    protected function validateKey($key)
    {
        if (is_string($key)) {
            return $key;
        }

        throw new CacheInvalidArgumentException("{$key} is not a valid key");
    }

    public function canGet($key)
    {
        try {
            return $this->has($key) || $this->valid($key);
        } catch (CacheInvalidArgumentException $exception) {
            return false;
        }
    }

    public function canSet($key, $value)
    {
        try {
            return $this->validateKey($key) === $key;
        } catch (CacheInvalidArgumentException $exception) {
            return false;
        }
    }

    public function canDelete($key)
    {
        try {
            return $this->has($key);
        } catch (CacheInvalidArgumentException $exception) {
            return false;
        }
    }
}
