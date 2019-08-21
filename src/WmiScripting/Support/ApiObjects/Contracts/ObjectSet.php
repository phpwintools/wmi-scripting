<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects\Contracts;

use ArrayAccess;
use PhpWinTools\WmiScripting\Models\Win32Model;
use PhpWinTools\WmiScripting\Collections\ModelCollection;

interface ObjectSet extends WbemObject, ArrayAccess
{
    public function count();

    /**
     * @return Win32Model[]|ModelCollection
     */
    public function getSet(): ModelCollection;

    public function instantiateModels(Win32Model $model): self;
}
