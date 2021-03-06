<?php

namespace PhpWinTools\WmiScripting\Testing\CallStacks;

class ApiObjectCallStack
{
    protected static $instance;

    protected $stack;

    public function __construct()
    {
        static::$instance = $this;
    }

    public static function newInstance()
    {
        return static::$instance = new static();
    }

    public static function instance()
    {
        return static::$instance ?? new static();
    }

    public function add(ApiObjectCall $call)
    {
        $this->stack[] = $call;

        return $this;
    }
}
