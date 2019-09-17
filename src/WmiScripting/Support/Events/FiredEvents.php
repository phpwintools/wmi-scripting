<?php

namespace PhpWinTools\WmiScripting\Support\Events;

use PhpWinTools\WmiScripting\Configuration\Config;

class FiredEvents
{
    protected $events = [];

    protected $event_cache = [];

    protected $event_ancestry_cache = [];

    protected $listener_cache = [];

    protected $config;

    public function __construct(Config $config = null)
    {
        $this->config = $config ?? Config::instance();
    }

    public function add(Event $event, $listeners = [])
    {
        if (!$this->track()) {
            return $this;
        }

        $this->events[][get_class($event)] = new FiredEvent($event, $listeners);

        $this->event_cache[get_class($event)][] = count($this->events) - 1;

        array_map(function ($parent) {
            $this->event_ancestry_cache[$parent][] = count($this->events) - 1;
        }, class_parents($event));

        array_map(function (Listener $listener) {
            $this->listener_cache[get_class($listener)][] = count($this->events) - 1;
        }, $listeners);
        
        return $this;
    }

    public function happened(string $event)
    {
        return array_key_exists($event, $this->event_cache)
            || array_key_exists($event, $this->event_ancestry_cache);
    }

    public function toStringArray()
    {
        return array_map(function ($event) {
            return [key($event) => $event[key($event)]->listenerTypes()];
        }, $this->events);
    }

    protected function track()
    {
        return $this->config->shouldTrackEvents();
    }
}
