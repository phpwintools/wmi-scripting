<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimProcess;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-process
 */
class Process extends CimProcess
{
    protected $uuid = '{8502C4DC-5FBB-11D2-AAC1-006008C78BC7}';

    protected $commandLine;

    protected $executablePath;

    protected $handleCount;

    protected $maximumWorkingSetSize;

    protected $minimumWorkingSetSize;

    protected $otherOperationCount;

    protected $otherTransferCount;

    protected $pageFaults;

    protected $pageFileUsage;

    protected $parentProcessId;

    protected $peakPageFileUsage;

    protected $peakVirtualSize;

    protected $peakWorkingSetSize;

    protected $privatePageCount;

    protected $processId;

    protected $quotaNonPagedPoolUsage;

    protected $quotaPagedPoolUsage;

    protected $quotaPeakNonPagedPoolUsage;

    protected $quotaPeakPagedPoolUsage;

    protected $readOperationCount;

    protected $readTransferCount;

    protected $sessionId;

    protected $threadCount;

    protected $virtualSize;

    protected $windowsVersion;

    protected $writeOperationCount;

    protected $writeTransferCount;
}
