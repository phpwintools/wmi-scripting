<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects\Contracts;

use PhpWinTools\WmiScripting\Win32Model;

interface ObjectItem extends WbemObject
{
    /**
     * @return Win32Model
     */
    public function getModel(): Win32Model;

    public function instantiateWin32Model(Win32Model $model = null): Win32Model;
}
