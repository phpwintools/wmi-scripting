<?php

namespace PhpWinTools\WmiScripting\Collections;

use Illuminate\Support\Collection;
use PhpWinTools\WmiScripting\Models\Win32Model;
use PhpWinTools\WmiScripting\Contracts\Arrayable;
use Illuminate\Support\HigherOrderCollectionProxy;
use PhpWinTools\WmiScripting\Support\ApiObjects\SWbemObject;
use Illuminate\Contracts\Support\Arrayable as IlluminateArrayable;

/**
 * @property-read HigherOrderCollectionProxy|SWbemObject map
 */
class ModelCollection extends Collection
{
    /**
     * @param callable|null $callback
     * @param null|mixed    $default
     *
     * @return Win32Model
     */
    public function first(callable $callback = null, $default = null)
    {
        return parent::first($callback, $default);
    }

    public function toArray()
    {
        return array_map(function ($value) {
            return $value instanceof Arrayable || $value instanceof IlluminateArrayable ? $value->toArray() : $value;
        }, $this->items);
    }
}
