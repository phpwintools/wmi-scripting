<?php

namespace PhpWinTools\WmiScripting\Support\Events;

class FiredEvent
{
    protected $event;

    /** @var array|Listener[] */
    protected $listeners = [];

    public function __construct(Event $event, array $listeners = [])
    {
        $this->event = $event;
        $this->listeners = $listeners;
    }

    public function event()
    {
        return $this->event;
    }

    public function eventType()
    {
        return get_class($this->event());
    }

    public function listeners()
    {
        return $this->listeners;
    }

    public function listenerTypes()
    {
        return array_map(function (Listener $listener) {
            return get_class($listener);
        }, $this->listeners());
    }
}
