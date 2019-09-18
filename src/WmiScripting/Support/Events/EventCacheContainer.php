<?php

namespace PhpWinTools\WmiScripting\Support\Events;

use PhpWinTools\WmiScripting\Containers\Container;

class EventCacheContainer extends Container
{
    protected $fired_event_key = 'actual';

    protected $event_ancestor_key = 'ancestors';

    protected $event_listeners_key = 'listeners';

    /** TODO: Implement PSR Cache for this */
    public function __construct()
    {
        $this->set($this->fired_event_key);
        $this->set($this->event_ancestor_key);
        $this->set($this->event_listeners_key);
    }

    public function addActual(string $event_name, int $index)
    {
        $this->append("{$this->fired_event_key}.{$event_name}", $index);

        return $this;
    }
}
