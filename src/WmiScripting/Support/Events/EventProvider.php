<?php

namespace PhpWinTools\WmiScripting\Support\Events;

use PhpWinTools\WmiScripting\Configuration\Config;
use function PhpWinTools\WmiScripting\Support\resolve;

class EventProvider
{
    /** @var self|null */
    protected static $instance;

    /** @var Config */
    protected $config;

    /** @var array|Listener[][] */
    protected $listeners = [];

    /** @var EventHistoryProvider */
    protected $history;

    public function __construct(Config $config = null)
    {
        $this->config = $config ?? Config::instance();

        $this->history = resolve(EventHistoryProvider::class);

        static::$instance = $this->config->registerProvider(EventProvider::class, $this);
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
            $this->listeners[get_class($event)] ?? [],
            $this->getAncestorListeners($event)
        );

        $this->history->add($event, $listeners);

        array_map(function (Listener $listener) use ($event) {
            $listener->react($event);
        }, $listeners);
    }

    public function trackEvents()
    {
        $this->config->trackEvents();

        return $this;
    }

    public function doNotTrackEvents()
    {
        $this->config->doNotTrackEvents();

        return $this;
    }

    public function history()
    {
        return $this->history;
    }

    protected function getAncestorListeners(Event $event)
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
