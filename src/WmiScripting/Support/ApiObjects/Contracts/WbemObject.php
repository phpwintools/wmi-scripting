<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects\Contracts;

use PhpWinTools\WmiScripting\Contracts\Jsonable;
use PhpWinTools\WmiScripting\Contracts\HasAttributes;

interface WbemObject extends HasAttributes, Jsonable
{
}
