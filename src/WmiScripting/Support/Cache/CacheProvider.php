<?php

namespace PhpWinTools\WmiScripting\Support\Cache;

use Closure;
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
use PhpWinTools\WmiScripting\Support\Cache\Events\CacheKeyDeleted;
use PhpWinTools\WmiScripting\Support\Cache\Events\CacheKeyExpired;
use PhpWinTools\WmiScripting\Exceptions\CacheInvalidArgumentException;

class CacheProvider implements CacheInterface
{
    /** @var Config */
    protected $config;

    /** @var CacheDriver */
    protected $driver;

    /** @var EventProvider */
    protected $events;

    /** @var array|Event[] */
    protected $delayedEvents = [];

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
     * @throws CacheInvalidArgumentException|InvalidArgumentException
     *
     * @return iterable|mixed|null
     */
    public function get($key, $default = null)
    {
        if (is_array($key)) {
            return $this->getMultiple($key, $default);
        }

        $value = $this->driver()->get($key, null);

        if ($this->driver()->expired($key)) {
            $this->removeExpired($key, $value);
            $value = null;
        }

        if (is_null($value)) {
            $value = reduce_value($default);
            $this->fire(new CacheMissed($this->driver(), $key));
        } else {
            $this->fire(new CacheHit($this->driver(), $key, $value));
        }

        return $value;
    }

    /**
     * Accepts either a string or a key-value pair array. If given an array then the second value is used as the
     * ttl, unless $ttl is explicitly set.
     *
     * @param string|array $key
     * @param mixed        $value
     * @param null|int     $ttl
     *
     * @throws CacheInvalidArgumentException|InvalidArgumentException
     *
     * @return bool
     */
    public function set($key, $value = null, $ttl = null)
    {
        if (is_array($key)) {
            return $this->setMultiple($key, $ttl ?? $value);
        }

        $result = $this->driver()->set($key, $value, $ttl);

        if ($result) {
            $this->fire(new CacheKeyStored($this->driver(), $key, $value, $ttl));
        }

        return $result;
    }

    /**
     * @param string|array $key
     *
     * @throws CacheInvalidArgumentException|InvalidArgumentException
     *
     * @return bool
     */
    public function delete($key)
    {
        if (is_array($key)) {
            return $this->deleteMultiple($key);
        }

        $result = $this->driver()->delete($key);

        if ($result) {
            $this->fire(new CacheKeyDeleted($this->driver(), $key));
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function clear()
    {
        $result = $this->driver->clear();

        if ($result) {
            $this->fire(new CacheCleared($this->driver));
        }

        return $result;
    }

    /**
     * @param iterable $keys
     * @param null     $default
     *
     * @throws InvalidArgumentException|CacheInvalidArgumentException
     *
     * @return iterable
     */
    public function getMultiple($keys, $default = null)
    {
        $hit = [];

        foreach ($keys as $key) {
            if ($this->driver()->expired($key)) {
                $this->removeExpired($key);
            }

            if ($this->driver()->canGet($key)) {
                $hit[$key] = $key;
            }
        }

        $results = $this->driver()->getMultiple($keys, $default);

        foreach ($results as $key => $value) {
            if (array_key_exists($key, $hit)) {
                $this->fire(new CacheHit($this->driver(), $key, $value));
            } else {
                $this->fire(new CacheMissed($this->driver(), $key));
            }
        }

        return $results;
    }

    /**
     * @param iterable $values
     * @param null     $ttl
     *
     * @return bool
     */
    public function setMultiple($values, $ttl = null)
    {
        return $this->multiple('set', $values, $ttl) ? $this->driver()->setMultiple($values, $ttl) : false;
    }

    /**
     * @param iterable $keys
     *
     * @return bool
     */
    public function deleteMultiple($keys)
    {
        return $this->multiple('delete', $keys) ? $this->driver()->deleteMultiple($keys) : false;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return $this->driver()->has($key);
    }

    /**
     * @return bool
     */
    public function empty(): bool
    {
        return $this->driver()->empty();
    }

    /**
     * @return bool
     */
    public function notEmpty(): bool
    {
        return $this->driver()->notEmpty();
    }

    /**
     * @return CacheDriver
     */
    public function driver()
    {
        return $this->driver;
    }

    /**
     * @param string         $method
     * @param iterable       $keys
     * @param null|int|mixed $ttl
     *
     * @return bool
     */
    protected function multiple(string $method, $keys, $ttl = null): bool
    {
        foreach ($keys as $key => $value) {
            $result = false;

            if ($method === 'set' && $result = $this->driver()->canSet($key, $value)) {
                $this->delayedFire(new CacheKeyStored($this->driver, $key, $value, $ttl));
            }

            if ($method === 'delete' && $result = $this->driver()->canDelete($value)) {
                $this->delayedFire(new CacheKeyDeleted($this->driver, $value));
            }

            if ($result === false) {
                $this->flushDelayedEvents(false);
                return false;
            }
        }

        $this->flushDelayedEvents();

        return true;
    }

    /**
     * @param      $key
     * @param null $value
     *
     * @throws CacheInvalidArgumentException|InvalidArgumentException
     *
     * @return self
     */
    protected function removeExpired($key, $value = null): self
    {
        if ($this->driver()->expired($key)) {
            $this->fire(new CacheKeyExpired($this->driver(), $key, $value));
            $this->delete($key);
        }


        return $this;
    }

    /**
     * @param Event $event
     *
     * @return self
     */
    protected function fire(Event $event): self
    {
        $this->events->fire($event);

        return $this;
    }

    /**
     * @param bool $fire
     *
     * @return self
     */
    protected function flushDelayedEvents(bool $fire = true): self
    {
        if ($fire) {
            array_map(function (Event $event) {
                $this->fire($event);
            }, $this->delayedEvents);
        }

        $this->delayedEvents = [];

        return $this;
    }

    /**
     * @param Event $event
     *
     * @return $this
     */
    protected function delayedFire(Event $event): self
    {
        $this->delayedEvents[] = $event;

        return $this;
    }
}
