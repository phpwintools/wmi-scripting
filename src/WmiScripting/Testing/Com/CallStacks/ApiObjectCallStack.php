<?php

namespace PhpWinTools\WmiScripting\Testing\Com\CallStacks;

class ApiObjectCallStack
{
    protected static $instance;

    protected $stack;

    public function __construct()
    {
        static::$instance = $this;
    }

    public static function instance()
    {
        return static::$instance ?? new static;
    }

    public function add(ApiObjectCall $call)
    {
        $this->stack[] = $call;

        return $this;
    }
}
