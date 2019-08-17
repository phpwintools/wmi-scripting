<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimFan;

/**
 * Class Fan
 * @package App\Transformers\Com\Wmi\Win32\Providers
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-fan
 */
class Fan extends CimFan
{
    protected $uuid = '{464FFAB5-946F-11d2-AAE2-006008C78BC7}';
}
