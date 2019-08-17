<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-usbhub
 */
class CimUSBHub extends CimUSBDevice
{
    protected $gangSwitched;

    protected $numberOfPorts;
}
