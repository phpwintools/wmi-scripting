<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-process
 */
class CimProcess extends CimLogicalElement
{
    protected $uuid = '{8502C566-5FBB-11D2-AAC1-006008C78BC7}';

    protected $creationClassName;

    protected $creationDate;

    protected $cSCreationClassName;

    protected $cSName;

    protected $executionState;

    protected $handle;

    protected $kernelModeTime;

    protected $oSCreationClassName;

    protected $oSName;

    protected $priority;

    protected $terminationDate;

    protected $userModeTime;

    protected $workingSetSize;
}
