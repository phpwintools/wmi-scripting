<?php

namespace PhpWinTools\WmiScripting\Support\Cache;

use Psr\SimpleCache\CacheInterface;
use PhpWinTools\WmiScripting\Exceptions\CacheInvalidArgumentException;

class ArrayDriver extends CacheDriver implements CacheInterface
{
    protected $store = [];

    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            return $this->store[$key];
        }

        return is_callable($default) ? $default() : $default;
    }

    public function set($key, $value, $ttl = null)
    {
        $this->store[$this->validateKey($key)] = $value;

        return true;
    }

    public function delete($key)
    {
        if ($this->has($key)) {
            unset($this->store[$key]);
            return true;
        }

        return false;
    }

    public function clear()
    {
        $this->store = [];

        return true;
    }

    public function getMultiple($keys, $default = null)
    {
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }

        return $result;
    }

    public function setMultiple($values, $ttl = null)
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }

        return true;
    }

    public function deleteMultiple($keys)
    {
        foreach ($keys as $key) {
            if ($this->delete($key)) {
                continue;
            } else {
                return false;
            }
        }

        return true;
    }

    public function has($key)
    {
        return array_key_exists($this->validateKey($key), $this->store);
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
}
