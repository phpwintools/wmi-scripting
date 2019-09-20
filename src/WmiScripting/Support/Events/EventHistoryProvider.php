<?php

namespace PhpWinTools\WmiScripting\Support\Events;

use Closure;
use Countable;
use PhpWinTools\WmiScripting\Configuration\Config;

class EventHistoryProvider implements Countable
{
    /** @var Config */
    protected $config;

    /** @var array|FiredEvent[] */
    protected $fired_events = [];

    /** @var array */
    protected $event_cache = [
        'actual' => [],
        'ancestors' => [],
        'listeners' => [],
    ];

    public function __construct(Config $config = null)
    {
        $this->config = $config ?? Config::instance();
    }

    public function cache()
    {
        return $this->event_cache;
    }

    /**
     * @param Event            $event
     * @param array|Listener[] $listeners
     *
     * @return self
     */
    public function add(Event $event, array $listeners = []): self
    {
        if ($this->shouldNotTrack()) {
            return $this;
        }

        $this->fired_events[] = new FiredEvent($event, $listeners);

        return $this->cacheEvent($event, $listeners);
    }

    /**
     * @return array|FiredEvent[]
     */
    public function all()
    {
        return $this->fired_events;
    }

    /**
     * @param string              $event
     * @param array|Closure|mixed $default
     *
     * @return array|FiredEvent[]|mixed
     */
    public function get(string $event, $default = [])
    {
        return $this->findEvents($this->getFiredEventKeys($event), $default);
    }

    /**
     * @param string              $listener
     * @param array|Closure|mixed $default
     *
     * @return array|FiredEvent[]|mixed
     */
    public function getFromListener(string $listener, $default = [])
    {
        return $this->findEvents($this->event_cache['listeners'][$listener] ?? [], $default);
    }

    /**
     * @param string $event
     *
     * @return array|mixed
     */
    public function getFiredEventKeys(string $event)
    {
        $keys = [];

        if ($this->wasFiredByName($event)) {
            $keys = $this->event_cache['actual'][$event];
        }

        if ($this->wasFiredByDescendant($event)) {
            $keys = array_merge($keys, $this->event_cache['ancestors'][$event]);
        }

        return $keys;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->fired_events);
    }

    /**
     * @param string|null $event
     *
     * @return int
     */
    public function eventCount(string $event = null): int
    {
        return is_null($event) ? $this->count() : count($this->get($event));
    }

    /**
     * @return int
     */
    public function lastIndex(): int
    {
        return $this->count() - 1;
    }

    /**
     * @param string $event
     *
     * @return bool
     */
    public function hasFired(string $event): bool
    {
        return $this->wasFiredByName($event) || $this->wasFiredByDescendant($event);
    }

    /**
     * @param string $event
     *
     * @return bool
     */
    public function hasNotFired(string $event): bool
    {
        return $this->hasFired($event) === false;
    }

    /**
     * @param string $event
     *
     * @return bool
     */
    public function wasFiredByName(string $event): bool
    {
        return array_key_exists($event, $this->event_cache['actual']);
    }

    /**
     * @param string $event
     *
     * @return bool
     */
    public function wasFiredByDescendant(string $event): bool
    {
        return array_key_exists($event, $this->event_cache['ancestors']);
    }

    /**
     * @param array               $event_keys
     * @param array|Closure|mixed $default
     *
     * @return array|FiredEvent[]|mixed
     */
    protected function findEvents(array $event_keys = [], $default = [])
    {
        $events = array_values(array_map(function ($event_key) {
            return $this->fired_events[$event_key];
        }, $event_keys));

        if (empty($events)) {
            return is_callable($default) ? $default() : $default;
        }

        return $events;
    }

    /**
     * @param Event            $event
     * @param array|Listener[] $listeners
     *
     * @return self
     */
    protected function cacheEvent(Event $event, array $listeners = []): self
    {
        $this->event_cache['actual'][get_class($event)][] = $this->lastIndex();

        return $this->cacheSet($event, 'ancestors', class_parents($event))->cacheSet($event, 'listeners', $listeners);
    }

    protected function cacheSet(Event $event, string $set_name, array $set)
    {
        array_map(function ($item) use ($set_name) {
            $item = is_object($item) ? get_class($item) : $item;
            $this->event_cache[$set_name][$item][] = $this->lastIndex();
        }, $set);

        return $this;
    }

    /**
     * @return bool
     */
    protected function shouldTrack(): bool
    {
        return $this->config->shouldTrackEvents();
    }

    /**
     * @return bool
     */
    protected function shouldNotTrack(): bool
    {
        return $this->shouldTrack() === false;
    }
}
