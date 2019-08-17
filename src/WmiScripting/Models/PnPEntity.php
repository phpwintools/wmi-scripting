<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimLogicalDevice;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-pnpentity
 */
class PnPEntity extends CimLogicalDevice
{
    protected $uuid = '{FE28FD98-C875-11d2-B352-00104BC97924}';

    protected $classGuid;

    protected $compatibleID;

    protected $hardwareID;

    protected $manufacturer;

    protected $pNPClass;

    protected $present;

    protected $service;
}
