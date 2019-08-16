<?php

namespace PhpWinTools\WmiScripting\Win32\Cim;

/**
 * Class CimUSBController
 * @package App\Transformers\Com\Wmi\Win32\Cim
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-usbcontroller
 */
class CimUSBController extends CimController
{
    protected $uuid = '{FAF76B5B-798C-11D2-AAD1-006008C78BC7}';

    protected $manufacturer;
}
