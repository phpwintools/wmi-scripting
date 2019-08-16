<?php

namespace PhpWinTools\WmiScripting\Win32\Models;

use PhpWinTools\WmiScripting\Win32\Cim\CimStorageVolume;

/**
 * Class Volume
 * @package App\Transformers\Com\Wmi\Win32\Providers
 * Undocumented: https://docs.microsoft.com/en-us/previous-versions/windows/desktop/legacy/aa394515(v%3Dvs.85)
 * https://wutils.com/wmi/root/cimv2/win32_volume/
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
