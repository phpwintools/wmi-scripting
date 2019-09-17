<?php

namespace PhpWinTools\WmiScripting\Support\Events;

use PhpWinTools\WmiScripting\Configuration\Config;

class EventHandler
{
    /** @var self|null */
    protected static $instance;

    /** @var Config */
    protected $config;

    /** @var array|Listener[][] */
    protected $listeners = [];

    /** @var FiredEvents */
    protected $fired;

    public function __construct(Config $config = null)
    {
        $this->config = $config ?? Config::instance();
        $this->fired = new FiredEvents($config);

        static::$instance = $this;
    }

    public static function instance(Config $config = null)
    {
        if (static::$instance && $config) {
            static::$instance->config = $config;

            return static::$instance;
        }

        return new static($config);
    }

    public function subscribe($event, Listener $listener)
    {
        $this->listeners[$event][] = $listener;

        return $this;
    }

    public function fire(Event $event): void
    {
        $listeners = array_merge(
            $this->listeners[$event_name = get_class($event)] ?? [],
            $this->fireFromAncestry($event)
        );

        $this->fired->add($event, $listeners);

        array_map(function (Listener $listener) use ($event) {
            $listener->react($event);
        }, $listeners);
    }

    public function history()
    {
        return $this->fired;
    }

    protected function fireFromAncestry(Event $event)
    {
        $ancestors = class_parents($event);
        $listeners = [];

        foreach ($ancestors as $ancestor) {
            if (array_key_exists($ancestor, $this->listeners)) {
                $listeners = array_merge($listeners, $this->listeners[$ancestor]);
            }
        }

        return $listeners;
    }
}
