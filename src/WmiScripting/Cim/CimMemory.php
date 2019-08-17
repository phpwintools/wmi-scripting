<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-memory
 */
class CimMemory extends CimStorageExtent
{
    protected $uuid = '{FAF76B64-798C-11D2-AAD1-006008C78BC7}';

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
