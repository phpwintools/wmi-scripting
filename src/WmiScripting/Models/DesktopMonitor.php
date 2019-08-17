<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimDesktopMonitor;

/**
 * Class DesktopMonitor
 * @package App\Transformers\Com\Wmi\Win32\Providers
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-desktopmonitor
 */
class DesktopMonitor extends CimDesktopMonitor
{
    protected $uuid = '{1008CCF0-7BFF-11D2-AAD2-006008C78BC7}';

    protected $monitorManufacturer;

    protected $monitorType;

    protected $pixelsPerXLogicalInch;

    protected $pixelsPerYLogicalInch;

}
