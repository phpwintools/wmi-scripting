<?php

namespace PhpWinTools\WmiScripting\Exceptions;

class UnresolvableClassException extends \InvalidArgumentException
{
    public static function default($class)
    {
        return new static("{$class} could not be resolved.");
    }
}
