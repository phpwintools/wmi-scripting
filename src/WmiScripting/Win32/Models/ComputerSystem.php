<?php

namespace PhpWinTools\WmiScripting\Win32\Models;

use PhpWinTools\WmiScripting\Win32\Cim\CimUnitaryComputerSystem;

/**
 * Class ComputerSystem
 * @package App\Transformers\Com\Wmi\Win32\Providers
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-computersystem
 */
class ComputerSystem extends CimUnitaryComputerSystem
{
    protected $uuid = '{8502C4B0-5FBB-11D2-AAC1-006008C78BC7}';

    protected $adminPasswordStatus;

    protected $automaticManagedPagefile;

    protected $automaticResetBootOption;

    protected $automaticResetCapability;

    protected $bootOptionOnLimit;

    protected $bootOptionOnWatchDog;

    protected $bootROMSupported;

    protected $bootupState;

    protected $bootStatus;

    protected $chassisBootupState;

    protected $chassisSKUNumber;

    protected $currentTimeZone;

    protected $daylightInEffect;

    protected $dNSHostName;

    protected $domain;

    protected $domainRole;

    protected $enableDaylightSavingsTime;

    protected $frontPanelResetStatus;

    protected $hypervisorPresent;

    protected $infraredSupported;

    protected $keyboardPasswordStatus;

    protected $manufacturer;

    protected $model;

    protected $networkServerModeEnabled;

    protected $numberOfLogicalProcessors;

    protected $numberOfProcessors;

    protected $oEMLogoBitmap;

    protected $oEMStringArray;

    protected $partOfDomain;

    protected $pauseAfterReset;

    protected $pCSystemType;

    protected $pCSystemTypeEx;

    protected $powerOnPasswordStatus;

    protected $powerSupplyState;

    protected $resetCount;

    protected $resetLimit;

    protected $supportContactDescription;

    protected $systemFamily;

    protected $systemSKUNumber;

    protected $systemStartupDelay;

    protected $systemStartupOptions;

    protected $systemStartupSetting;

    protected $systemType;

    protected $thermalState;

    protected $totalPhysicalMemory;

    protected $userName;

    protected $wakeUpType;

    protected $workgroup;
}
