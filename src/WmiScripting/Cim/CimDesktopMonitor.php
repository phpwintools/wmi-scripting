<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-desktopmonitor
 */
class CimDesktopMonitor extends CimDisplay
{
    protected $uuid = '{1008CCE8-7BFF-11D2-AAD2-006008C78BC7}';

    protected $bandwidth;

    protected $displayType;

    protected $screenHeight;

    protected $screenWidth;
}
