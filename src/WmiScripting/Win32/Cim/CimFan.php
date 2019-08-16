<?php

namespace PhpWinTools\WmiScripting\Win32\Cim;

/**
 * Class CimFan
 * @package App\Transformers\Com\Wmi\Win32\Cim
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-fan
 */
class CimFan extends CimCoolingDevice
{
    protected $uuid = '{0A59C856-E3D4-11d2-8601-0000F8102E5F}';

    protected $desiredSpeed;

    protected $variableSpeed;

    public function setSpeed()
    {
        //
    }
}
