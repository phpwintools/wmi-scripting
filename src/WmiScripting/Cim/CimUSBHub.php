<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * Class CimUSBHub
 * @package App\Transformers\Com\Wmi\Win32\Cim
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-usbhub
 */
class CimUSBHub extends CimUSBDevice
{
    protected $gangSwitched;

    protected $numberOfPorts;
}
