<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimStorageExtent;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-smbiosmemory
 */
class SMBIOSMemory extends CimStorageExtent
{
    protected $uuid = '{FECB095B-F0FA-11d2-8617-0000F8102E5F}';

    protected $additionalErrorData;

    protected $correctableError;

    protected $endingAddress;

    protected $errorAccess;

    protected $errorAddress;

    protected $errorData;

    protected $errorDataOrder;

    protected $errorInfo;

    protected $errorResolution;

    protected $errorTime;

    protected $errorTransferSize;

    protected $otherErrorDescription;

    protected $startingAddress;

    protected $systemLevelAddress;

}
