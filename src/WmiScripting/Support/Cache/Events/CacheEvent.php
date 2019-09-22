<?php

namespace PhpWinTools\WmiScripting\Support\Cache\Events;

use PhpWinTools\WmiScripting\Support\Events\Event;
use PhpWinTools\WmiScripting\Support\Events\Payload;
use PhpWinTools\WmiScripting\Support\Cache\CacheDriver;

class CacheEvent extends Event
{
    public function __construct(CacheDriver $driver, $key = null, $value = null, $ttl = null)
    {
        parent::__construct(
            (new Payload())->add('driver', $driver)->add('key', $key)->add('value', $value)->add('ttl', $ttl)
        );
    }
}
