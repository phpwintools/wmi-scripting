<?php

namespace PhpWinTools\WmiScripting\Win32\Models;

use PhpWinTools\WmiScripting\Win32\Cim\CimSystemDevice;

/**
 * Class SystemDevices
 * @package App\Transformers\Com\Wmi\Win32\Providers
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-systemdevices
 */
class SystemDevices extends CimSystemDevice
{
    protected $uuid = '{8502C4F4-5FBB-11D2-AAC1-006008C78BC7}';
}
