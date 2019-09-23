<?php

namespace PhpWinTools\WmiScripting\Support\Cache;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Psr\SimpleCache\CacheInterface;

class ArrayDriver extends CacheDriver implements CacheInterface
{
    /** @var array */
    protected $store = [];

    public function get($key, $default = null)
    {
        return Arr::get($this->store, "{$this->validateKey($key)}.value", $default);
    }

    public function set($key, $value, $ttl = null)
    {
        return is_array(Arr::set(
            $this->store,
            $this->validateKey($key),
            [
                'value' => $value,
                'expires' => is_null($ttl) ? null : Carbon::now()->addSeconds($ttl)
            ]
        ));
    }

    public function delete($key)
    {
        if ($this->has($key)) {
            Arr::forget($this->store, $key);
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
            $this->set($key, $value, $ttl);
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

    public function expired($key): bool
    {
        $expires = Arr::get($this->store, "{$key}.expires");

        return is_null($expires) ? false : Carbon::now()->greaterThan($expires);
    }

    public function empty(): bool
    {
        return empty($this->store);
    }

    public function has($key)
    {
        return Arr::has($this->store, $this->validateKey($key));
    }
}
