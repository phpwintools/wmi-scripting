<?php

namespace PhpWinTools\WmiScripting\Concerns;

use PhpWinTools\WmiScripting\Collections\ArrayCollection;

trait CanCollect
{
    public function collect(array $array)
    {
        return new ArrayCollection($array);
    }
}
