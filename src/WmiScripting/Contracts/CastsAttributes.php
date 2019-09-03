<?php

namespace PhpWinTools\WmiScripting\Contracts;

interface CastsAttributes extends HasAttributes
{
    public function getCasts(): array;

    public function getCast($attribute);
}
