<?php

namespace PhpWinTools\WmiScripting\Win32\Models;

use PhpWinTools\WmiScripting\Win32\Cim\CimTemperatureSensor;

/**
 * Class TemperatureProbe
 * @package App\Transformers\Com\Wmi\Win32\Providers
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-temperatureprobe
 */
class TemperatureProbe extends CimTemperatureSensor
{
    protected $uuid = '{464FFABB-946F-11d2-AAE2-006008C78BC7}';
}
