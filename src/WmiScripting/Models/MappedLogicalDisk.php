<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimLogicalDisk;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-mappedlogicaldisk
 */
class MappedLogicalDisk extends CimLogicalDisk
{
    protected $uuid = '{BCF02FFE-5560-4de2-B419-272918693426}';

    protected $compressed;

    protected $fileSystem;

    protected $maximumComponentLength;

    protected $providerName;

    protected $quotasDisabled;

    protected $quotasIncomplete;

    protected $quotasRebuilding;

    protected $sessionID;

    protected $supportsDiskQuotas;

    protected $supportsFileBasedCompression;

    protected $volumeName;

    protected $volumeSerialNumber;

}
