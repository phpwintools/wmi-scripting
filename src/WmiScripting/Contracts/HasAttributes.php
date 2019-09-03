<?php

namespace PhpWinTools\WmiScripting\Contracts;

interface HasAttributes extends Arrayable
{
    public function getAttribute($attribute, $default = null);
}
