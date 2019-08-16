<?php

namespace PhpWinTools\WmiScripting\Win32\Cim;

/**
 * Class CimUserDevice
 * @package App\Transformers\Com\Wmi\Win32\Cim
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-userdevice
 */
class CimUserDevice extends CimLogicalDevice
{
    protected $uuid = '{8502C533-5FBB-11D2-AAC1-006008C78BC7}';

    protected $isLocked;
}
