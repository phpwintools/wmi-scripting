<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-usbdevice
 */
class CimUSBDevice extends CimLogicalDevice
{
    protected $uuid = '';

    protected $classCode;

    protected $currentAlternateSettings;

    protected $currentConfigValue;

    protected $numberOfConfigs;

    protected $protocolCode;

    protected $subclassCode;

    protected $uSBVersion;

    public function getDescriptor()
    {
        //
    }
}
