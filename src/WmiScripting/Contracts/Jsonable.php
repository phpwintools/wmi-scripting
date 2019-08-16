<?php

namespace PhpWinTools\WmiScripting\Contracts;

interface Jsonable extends Arrayable
{
    public function toJson(): string;

    public function toString(): string;

    public function __toString();
}
