<?php

namespace PhpWinTools\WmiScripting\Exceptions;

use Psr\SimpleCache\InvalidArgumentException as BaseException;

class CacheInvalidArgumentException extends InvalidArgumentException implements BaseException
{

}
