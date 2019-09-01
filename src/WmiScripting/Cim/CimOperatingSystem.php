<?php

namespace PhpWinTools\WmiScripting\Cim;

use PhpWinTools\WmiScripting\MappingStrings\OSType;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-operatingsystem
 */
class CimOperatingSystem extends CimLogicalElement
{
    protected $uuid = '{8502C565-5FBB-11D2-AAC1-006008C78BC7}';

    protected $creationClassName;

    protected $cSCreationClassName;

    protected $cSName;

    protected $currentTimeZone;

    protected $distributed;

    protected $freePhysicalMemory;

    protected $freeSpaceInPagingFiles;

    protected $freeVirtualMemory;

    protected $lastBootUpTime;

    protected $localDateTime;

    protected $maxNumberOfProcesses;

    protected $maxProcessMemorySize;

    protected $numberOfLicensedUsers;

    protected $numberOfProcesses;

    protected $numberOfUsers;

    protected $oSType;

    protected $otherTypeDescription;

    protected $sizeStoredInPagingFiles;

    protected $totalSwapSpaceSize;

    protected $totalVirtualMemorySize;

    protected $totalVisibleMemorySize;

    protected $version;

    public function getOSTypeAttribute($value)
    {
        return $this->mapConstant(OSType::class, $value);
    }

    public function reboot()
    {
        //
    }

    public function shutdown()
    {
        //
    }
}
