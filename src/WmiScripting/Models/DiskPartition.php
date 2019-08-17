<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimDiskPartition;

/**
 * Class DiskPartition
 * @package App\Transformers\Com\Wmi\Win32\Providers
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-diskpartition
 */
class DiskPartition extends CimDiskPartition
{
    protected $uuid = '{8502C4B8-5FBB-11D2-AAC1-006008C78BC7}';

    protected $additionalAvailability;

    protected $identifyingDescriptions;

    protected $maxQuiesceTime;

    protected $otherIdentifyingInfo;

    protected $powerOnHours;

    protected $totalPowerOnHours;

    protected $bootPartition;

    protected $diskIndex;

    protected $hiddenSectors;

    protected $index;

    protected $rewritePartition;

    protected $size;

    protected $startingOffset;

    protected $type;

}
