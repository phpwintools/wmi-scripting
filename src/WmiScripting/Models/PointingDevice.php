<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimPointingDevice;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-pointingdevice
 */
class PointingDevice extends CimPointingDevice
{
    protected $uuid = '{8502C4B4-5FBB-11D2-AAC1-006008C78BC7}';

    protected $deviceInterface;

    protected $doubleSpeedThreshold;

    protected $hardwareType;

    protected $infFileName;

    protected $infSection;

    protected $manufacturer;

    protected $quadSpeedThreshold;

    protected $sampleRate;

    protected $synch;

}
