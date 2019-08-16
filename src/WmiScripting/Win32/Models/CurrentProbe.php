<?php

namespace PhpWinTools\WmiScripting\Win32\Models;

use PhpWinTools\WmiScripting\Win32\Cim\CimCurrentSensor;

/**
 * Class CurrentProbe
 * @package App\Transformers\Com\Wmi\Win32\Providers
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-currentprobe
 */
class CurrentProbe extends CimCurrentSensor
{
    protected $uuid = '{464FFABA-946F-11d2-AAE2-006008C78BC7}';
}
