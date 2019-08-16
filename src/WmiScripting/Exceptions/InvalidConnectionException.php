<?php

namespace PhpWinTools\WmiScripting\Exceptions;

class InvalidConnectionException extends \InvalidArgumentException
{
    public static function new($connection_name)
    {
        return new static("Could not find connection: {$connection_name}");
    }
}
