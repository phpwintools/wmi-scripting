<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimStorageVolume;

/**
 * @link https://docs.microsoft.com/en-us/previous-versions/windows/desktop/legacy/aa394515(v%3Dvs.85)
 * @link https://wutils.com/wmi/root/cimv2/win32_volume/
 */
class Volume extends CimStorageVolume
{
    protected $automount;

    protected $bootVolume;

    protected $capacity;

    protected $compressed;

    protected $dirtyBitSet;

    protected $driveLetter;

    protected $driveType;

    protected $fileSystem;

    protected $freeSpace;

    protected $indexingEnabled;

    protected $label;

    protected $maximumFileNameLength;

    protected $pageFilePresent;

    protected $quotasEnabled;

    protected $quotasIncomplete;

    protected $quotasRebuilding;

    protected $serialNumber;

    protected $supportsDiskQuotas;

    protected $supportsFileBasedCompression;

    protected $systemVolume;
}
