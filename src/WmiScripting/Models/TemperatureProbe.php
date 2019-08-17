<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimTemperatureSensor;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-temperatureprobe
 */
class TemperatureProbe extends CimTemperatureSensor
{
    protected $uuid = '{464FFABB-946F-11d2-AAE2-006008C78BC7}';
}
