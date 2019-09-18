<?php

namespace PhpWinTools\WmiScripting\Containers;

use Illuminate\Support\Arr;

class Container
{
    protected $container;

    public function get($key, $default = null)
    {
        return Arr::get($this->container, $key, $default);
    }

    public function append($key, $value)
    {
        if (is_null($this->get($key))) {
            //
        }
    }

    public function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            Arr::set($this->container, $key, $value);
        }

        return $this;
    }

    public function merge(array $array)
    {
        foreach (Arr::dot($array) as $key => $value) {
            $this->container = Arr::add($this->container, $key, $value);
        }

        return $this;
    }
}
