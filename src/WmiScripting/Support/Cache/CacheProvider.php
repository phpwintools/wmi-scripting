<?php

namespace PhpWinTools\WmiScripting\Support\Cache;

use Closure;
use DateInterval;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Support\Events\Event;
use PhpWinTools\WmiScripting\Support\Events\EventProvider;
use function PhpWinTools\WmiScripting\Support\reduce_value;
use PhpWinTools\WmiScripting\Support\Cache\Events\CacheHit;
use PhpWinTools\WmiScripting\Support\Cache\Events\CacheMissed;
use PhpWinTools\WmiScripting\Support\Cache\Events\CacheCleared;
use PhpWinTools\WmiScripting\Support\Cache\Events\CacheKeyStored;
use PhpWinTools\WmiScripting\Support\Cache\Events\CacheKeyExpired;
use PhpWinTools\WmiScripting\Support\Cache\Events\CacheKeyForgotten;
use PhpWinTools\WmiScripting\Exceptions\CacheInvalidArgumentException;

class CacheProvider implements CacheInterface
{
    /** @var Config */
    protected $config;

    /** @var CacheDriver */
    protected $driver;

    /** @var EventProvider */
    protected $events;

    public function __construct(Config $config = null, CacheDriver $driver = null, EventProvider $events = null)
    {
        $this->config = $config ?? Config::instance();
        $this->driver = $driver ?? $this->config->getCacheDriver();
        $this->events = $events ?? $this->config->eventProvider();
    }

    /**
     * @param string|array       $key
     * @param Closure|null|mixed $default
     *
     * @throws InvalidArgumentException|CacheInvalidArgumentException
     *
     * @return iterable|mixed|null
     */
    public function get($key, $default = null)
    {
        if (is_array($key)) {
            return $this->getMultiple($key);
        }

        $value = $this->driver()->get($key, null);

        if ($this->driver()->expired($key)) {
            $this->events(new CacheKeyExpired($this->driver(), $key, $value));
            $this->delete($key);
            $value = null;
        }

        if (is_null($value)) {
            $this->events(new CacheMissed($this->driver(), $key));
            $value = reduce_value($value);
        } else {
            $this->events(new CacheHit($this->driver(), $key, $value));
        }

        return $value;
    }

    /**
     * @param string|array          $key
     * @param mixed                 $value
     * @param null|int|DateInterval $ttl
     *
     * @throws InvalidArgumentException|CacheInvalidArgumentException
     *
     * @return bool
     */
    public function set($key, $value, $ttl = null)
    {
        if (is_array($key)) {
            return $this->setMultiple($key, $ttl);
        }

        $result = $this->driver()->set($key, $value, $ttl);

        if ($result) {
            $this->events(new CacheKeyStored($this->driver(), $key, $value, $ttl));
        }

        return $result;
    }

    public function delete($key)
    {
        if (is_array($key)) {
            return $this->deleteMultiple($key);
        }

        $result = $this->driver()->delete($key);

        if ($result) {
            $this->events(new CacheKeyForgotten($this->driver(), $key));
        }

        return $result;
    }

    public function clear()
    {
        $result = $this->driver->clear();

        if ($result) {
            $this->events(new CacheCleared($this->driver));
        }

        return $result;
    }

    public function getMultiple($keys, $default = null)
    {
        $this->multiple('get', $keys);

        return $this->driver()->getMultiple($keys, $default);
    }

    public function setMultiple($values, $ttl = null)
    {
        $this->multiple('set', $values, $ttl);

        return $this->driver()->setMultiple($values, $ttl);
    }

    public function deleteMultiple($keys)
    {
        $this->multiple('delete', $keys);

        return $this->driver()->deleteMultiple($keys);
    }

    public function has($key)
    {
        return $this->driver()->has($key);
    }

    public function driver()
    {
        return $this->driver;
    }

    public function setEventProvider(EventProvider $event)
    {
        $this->events = $event;

        return $this;
    }

    protected function multiple(string $method, $keys, $ttl = null)
    {
        foreach ($keys as $key => $value) {
            if ($method === 'get' && $this->driver()->canGet($key)) {
                $this->events(new CacheHit($this->driver, $key, $this->driver()->get($key)));
            }

            if ($method === 'set' && $this->driver()->canSet($key, $value)) {
                $this->events(new CacheKeyStored($this->driver, $key, $value, $ttl));
            }

            if ($method === 'delete' && $this->driver()->canDelete($key)) {
                $this->events(new CacheKeyForgotten($this->driver, $key));
            }
        }
    }

    protected function events(Event $event, string $key = null)
    {
        $this->events->fire($event);

        return $this;
    }
}
