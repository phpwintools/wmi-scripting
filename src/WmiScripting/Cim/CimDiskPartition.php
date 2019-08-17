<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * Class CimDiskPartition
 * @package App\Transformers\Com\Wmi\Win32\Cim
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-diskpartition
 */
class CimDiskPartition extends CimStorageExtent
{
    protected $uuid = '{8502C541-5FBB-11D2-AAC1-006008C78BC7}';

    protected $bootable;

    protected $primaryPartition;
}
