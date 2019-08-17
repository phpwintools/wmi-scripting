<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * Class CimSerialController
 * @package App\Transformers\Com\Wmi\Win32\Cim
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-serialcontroller
 */
class CimSerialController extends CimController
{
    protected $uuid = '{8502C554-5FBB-11D2-AAC1-006008C78BC7}';

    protected $capabilities;

    protected $capabilityDescriptions;

    protected $maxBaudRate;
}