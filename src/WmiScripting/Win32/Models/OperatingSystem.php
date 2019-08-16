<?php

namespace PhpWinTools\WmiScripting\Win32\Models;

use PhpWinTools\WmiScripting\Win32\Cim\CimOperatingSystem;

/**
 * Class OperatingSystem
 * @package App\Transformers\Com\Wmi\Win32\Providers
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-operatingsystem
 */
class OperatingSystem extends CimOperatingSystem
{
    protected $uuid = '{8502C4DE-5FBB-11D2-AAC1-006008C78BC7}';

    protected $bootDevice;

    protected $buildNumber;

    protected $buildType;

    protected $codeSet;

    protected $countryCode;

    protected $cSDVersion;

    protected $dataExecutionPrevention_Available;

    protected $dataExecutionPrevention_32BitApplications;

    protected $dataExecutionPrevention_Drivers;

    protected $dataExecutionPrevention_SupportPolicy;

    protected $debug;

    protected $encryptionLevel;

    protected $foregroundApplicationBoost = 2;

    protected $largeSystemCache;

    protected $locale;

    protected $manufacturer;

    protected $mUILanguages;

    protected $operatingSystemSKU;

    protected $organization;

    protected $oSArchitecture;

    protected $oSLanguage;

    protected $oSProductSuite;

    protected $pAEEnabled;

    protected $plusProductID;

    protected $plusVersionNumber;

    protected $portableOperatingSystem;

    protected $primary;

    protected $productType;

    protected $registeredUser;

    protected $serialNumber;

    protected $servicePackMajorVersion;

    protected $servicePackMinorVersion;

    protected $suiteMask;

    protected $systemDevice;

    protected $systemDirectory;

    protected $systemDrive;

    protected $windowsDirectory;

    protected $quantumLength;

    protected $quantumType;
}
