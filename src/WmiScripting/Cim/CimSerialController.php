<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-serialcontroller
 */
class CimSerialController extends CimController
{
    protected $uuid = '{8502C554-5FBB-11D2-AAC1-006008C78BC7}';

    protected $capabilities;

    protected $capabilityDescriptions;

    protected $maxBaudRate;
}
