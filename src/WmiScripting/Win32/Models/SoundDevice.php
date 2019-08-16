<?php

namespace PhpWinTools\WmiScripting\Win32\Models;

use PhpWinTools\WmiScripting\Win32\Cim\CimLogicalDevice;

class SoundDevice extends CimLogicalDevice
{
    protected $uuid = '{8502C50C-5FBB-11D2-AAC1-006008C78BC7}';

    protected $dMABufferSize;

    protected $manufacturer;

    protected $mPU401Address;

    protected $productName;
}
