<?php

namespace PhpWinTools\WmiScripting\Support\Bus\Events;

use PhpWinTools\WmiScripting\Configuration\Config;

class EventHandler
{
    /** @var self|null */
    protected static $instance;

    protected $config;

    public function __construct(Config $config = null)
    {
        $this->config = $config ?? Config::instance();

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

    /** @var array|Listener[][] */
    protected $listeners = [];

    protected $fired = [];

    public function subscribe($event, Listener $listener)
    {
        $this->listeners[$event][] = $listener;

        return $this;
    }

    public function fire(Event $event): void
    {
        $listeners = $this->listeners[$event_name = get_class($event)] ?? [];

        $this->fired[][$event] = new FiredEvent($event, $event->context(), $listeners);

        array_map(function (Listener $listener) use ($event) {
            $listener->react($event);
        }, $listeners);
    }

    public function fired(string $event_name = null)
    {
        return $event_name ? $this->fired[$event_name] ?? [] : $this->fired;
    }
}
