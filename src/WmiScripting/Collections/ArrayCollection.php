<?php

namespace PhpWinTools\WmiScripting\Collections;

use Illuminate\Support\Collection;

class ArrayCollection extends Collection
{
    /**
     * @param array $items
     *
     * @return ArrayCollection
     */
    public static function collect(array $items = [])
    {
        return new static($items);
    }
}
