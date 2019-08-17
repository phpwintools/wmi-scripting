<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimSCSIController;

/**
 * Class SCSIController
 * @package App\Transformers\Com\Wmi\Win32\Providers
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-scsicontroller
 */
class SCSIController extends CimSCSIController
{
    protected $uuid = '{8502C4C1-5FBB-11D2-AAC1-006008C78BC7}';

    protected $deviceMap;

    protected $driverName;

    protected $hardwareVersion;

    protected $index;

    protected $manufacturer;
}
