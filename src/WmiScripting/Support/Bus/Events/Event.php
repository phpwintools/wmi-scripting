<?php

namespace PhpWinTools\WmiScripting\Support\Bus\Events;

use PhpWinTools\WmiScripting\Support\Bus\Context;

abstract class Event
{
    protected $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function context(): Context
    {
        return $this->context;
    }
}
