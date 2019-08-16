<?php

namespace PhpWinTools\WmiScripting\Contracts\ApiObjects;

use PhpWinTools\WmiScripting\Win32\Win32Model;

interface ObjectItem extends WbemObject
{
    /**
     * @return Win32Model
     */
    public function getModel(): Win32Model;

    public function instantiateWin32Model(Win32Model $model = null): Win32Model;
}
