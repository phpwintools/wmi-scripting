<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-unitarycomputersystem
 */
class CimUnitaryComputerSystem extends CimComputerSystem
{
    protected $uuid = '{8502C526-5FBB-11D2-AAC1-006008C78BC7}';

    protected $initialLoadInfo;

    protected $lastLoadInfo;

    protected $powerManagementCapabilities;

    protected $powerManagementSupported;

    protected $powerState;

    protected $resetCapability;

    public function setPowerState()
    {
        //
    }
}
