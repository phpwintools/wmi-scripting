<?php

namespace PhpWinTools\WmiScripting\Support\Events;

use PhpWinTools\WmiScripting\Support\Events\Context;

class Event
{
    protected $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public static function fire(Context $context)
    {
        return new static($context);
    }

    public function context(): Context
    {
        return $this->context;
    }

    public function ancestry()
    {
        return class_parents(get_called_class());
    }
}
