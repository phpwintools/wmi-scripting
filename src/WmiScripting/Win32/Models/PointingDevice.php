<?php

namespace PhpWinTools\WmiScripting\Win32\Models;

use PhpWinTools\WmiScripting\Win32\Cim\CimPointingDevice;

/**
 * Class PointingDevice
 * @package App\Transformers\Com\Wmi\Win32\Providers
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-pointingdevice
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
