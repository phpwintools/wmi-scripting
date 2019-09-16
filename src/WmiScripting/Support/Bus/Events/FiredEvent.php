<?php

namespace PhpWinTools\WmiScripting\Support\Bus\Events;

use PhpWinTools\WmiScripting\Support\Bus\Context;

class FiredEvent
{
    protected $event;

    protected $context;

    /** @var array|Listener[] */
    protected $listeners = [];

    public function __construct(Event $event, Context $context, array $listeners = [])
    {
        $this->event = $event;
        $this->context = $context;
        $this->setListeners($listeners);
    }

    public function event()
    {
        return $this->event;
    }

    public function eventType()
    {
        return get_class($this->event());
    }

    public function context()
    {
        return $this->context;
    }

    public function listeners()
    {
        return $this->listeners;
    }

    public function listenerTypes()
    {
        return array_keys($this->listeners());
    }

    protected function setListeners(array $listeners = [])
    {
        array_map(function (Listener $listener) {
            $this->listeners[get_class($listener)] = $listener;
        }, $listeners);
    }
}
