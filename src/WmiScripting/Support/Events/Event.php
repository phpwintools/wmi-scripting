<?php

namespace PhpWinTools\WmiScripting\Support\Events;

class Event
{
    protected $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public static function new(Context $context)
    {
        return new static($context);
    }

    public function context(): Context
    {
        return $this->context;
    }
}
