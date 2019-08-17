<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimPCVideoController;

/**
 * Class VideoController
 * @package App\Transformers\Com\Wmi\Win32\Providers
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-videocontroller
 */
class VideoController extends CimPCVideoController
{
    protected $uuid = '{1008CCF1-7BFF-11D2-AAD2-006008C78BC7}';

    protected $adapterCompatibility;

    protected $adapterDACType;

    protected $adapterRAM;

    protected $colorTableEntries;

    protected $deviceSpecificPens;

    protected $ditherType;

    protected $driverDate;

    protected $driverVersion;

    protected $iCMIntent;

    protected $iCMMethod;

    protected $infFilename;

    protected $infSection;

    protected $installedDisplayDrivers;

    protected $monochrome;

    protected $reservedSystemPaletteEntries;

    protected $specificationVersion;

    protected $systemPaletteEntries;

    protected $videoModeDescription;

}
