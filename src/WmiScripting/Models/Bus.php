<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimLogicalDevice;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-bus
 */
class Bus extends CimLogicalDevice
{
    protected $uuid = '{8502C50E-5FBB-11D2-AAC1-006008C78BC7}';

    protected $busNum;

    protected $busType;
}
