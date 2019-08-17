<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimLogicalElement;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-session
 */
class Session extends CimLogicalElement
{
    protected $startTime;
}
