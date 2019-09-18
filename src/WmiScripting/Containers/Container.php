<?php

namespace PhpWinTools\WmiScripting\Containers;

use Illuminate\Support\Arr;
use PhpWinTools\WmiScripting\Exceptions\InvalidArgumentException;

class Container
{
    protected $container;

    public function get($key, $default = null)
    {
        return Arr::get($this->container, $key, $default);
    }

    public function append($key, $value)
    {
        $index = null;

        if (is_null($array = $this->get($key))) {
            $index = 0;
        }

        if ($array && Arr::isAssoc($array)) {
            throw new InvalidArgumentException("Cannot append to {$key} because it is associative.");
        }

        if ($array) {
            $index = count($array) - 1;
        }

        $this->set("{$key}.{$index}", $value);

        return $this;
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
