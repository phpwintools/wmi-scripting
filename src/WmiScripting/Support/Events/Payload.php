<?php

namespace PhpWinTools\WmiScripting\Support\Events;

class Payload
{
    protected $context = [];

    public function add($key, $value)
    {
        $this->context[$key] = $value;

        return $this;
    }

    public function get($key, $default = null)
    {
        if ($this->doesNotExist($key)) {
            return $default;
        }

        return $this->context[$key] ?? $default;
    }

    public function exists($key)
    {
        return array_key_exists($key, $this->context);
    }

    public function doesNotExist($key)
    {
        return $this->exists($key) === false;
    }
}
