<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-coolingdevice
 */
class CimCoolingDevice extends CimLogicalDevice
{
    protected $uuid = '{9565979A-7D80-11D2-AAD3-006008C78BC7}';

    protected $activeCooling;
}
