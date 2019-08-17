<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-controller
 */
class CimController extends CimLogicalDevice
{
    protected $uuid = '{8502C531-5FBB-11D2-AAC1-006008C78BC7}';

    protected $maxNumberControlled;

    protected $protocolSupported;

    protected $timeOfLastReset;
}
