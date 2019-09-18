<?php

namespace PhpWinTools\WmiScripting\Support\Events;

use PhpWinTools\WmiScripting\Configuration\Config;

class EventHistoryProvider
{
    /** @var array|FiredEvent[] */
    protected $fired_events = [];

    protected $event_cache = [];

    protected $event_ancestry_cache = [];

    protected $listener_cache = [];

    protected $config;

    public function __construct(Config $config = null)
    {
        $this->config = $config ?? Config::instance();
    }

    /**
     * @param Event            $event
     * @param array|Listener[] $listeners
     *
     * @return self
     */
    public function add(Event $event, $listeners = []): self
    {
        if ($this->shouldNotTrack()) {
            return $this;
        }

        $this->fired_events[] = new FiredEvent($event, $listeners);

        $this->event_cache[get_class($event)][] = count($this->fired_events) - 1;

        array_map(function ($parent) {
            $this->event_ancestry_cache[$parent][] = count($this->fired_events) - 1;
        }, class_parents($event));

        array_map(function (Listener $listener) {
            $this->listener_cache[get_class($listener)][] = count($this->fired_events) - 1;
        }, $listeners);
        
        return $this;
    }

    /**
     * @param string     $event
     * @param null|mixed $default
     *
     * @return array|FiredEvent[]|mixed
     */
    public function get(string $event, $default = [])
    {
        $events = array_values(array_map(function ($event_key) {
            return $this->fired_events[$event_key];
        }, $this->getFiredEventKeys($event)));

        if (empty($events)) {
            return is_callable($default) ? $default() : $default;
        }

        return $events;
    }

    public function getFiredEventKeys(string $event)
    {
        $keys = [];

        if ($this->wasFiredByName($event)) {
            $keys = $this->event_cache[$event];
        }

        if ($this->wasFiredByDescendant($event)) {
            $keys = array_merge($keys, $this->event_ancestry_cache[$event]);
        }

        return $keys;
    }

    /**
     * @return array|FiredEvent[]
     */
    public function all()
    {
        return $this->fired_events;
    }

    /**
     * @param string $event
     *
     * @return int
     */
    public function eventCount(string $event): int
    {
        $count = 0;

        if ($this->doNotHappen($event)) {
            return $count;
        }

        if ($this->wasFiredByName($event)) {
            $count = count($this->event_cache[$event]);
        }

        if ($this->wasFiredByDescendant($event)) {
            $count += count($this->event_ancestry_cache[$event]);
        }

        return $count;
    }

    /**
     * @param string $event
     *
     * @return bool
     */
    public function happened(string $event): bool
    {
        return $this->wasFiredByName($event) || $this->wasFiredByDescendant($event);
    }

    /**
     * @param string $event
     *
     * @return bool
     */
    public function doNotHappen(string $event): bool
    {
        return $this->happened($event) === false;
    }

    /**
     * @param string $event
     *
     * @return bool
     */
    public function wasFiredByName(string $event): bool
    {
        return array_key_exists($event, $this->event_cache);
    }

    /**
     * @param string $event
     *
     * @return bool
     */
    public function wasFiredByDescendant(string $event): bool
    {
        return array_key_exists($event, $this->event_ancestry_cache);
    }

    protected function shouldTrack(): bool
    {
        return $this->config->shouldTrackEvents();
    }

    protected function shouldNotTrack(): bool
    {
        return $this->shouldTrack() === false;
    }
}
