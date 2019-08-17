<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimCurrentSensor;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-currentprobe
 */
class CurrentProbe extends CimCurrentSensor
{
    protected $uuid = '{464FFABA-946F-11d2-AAE2-006008C78BC7}';
}
