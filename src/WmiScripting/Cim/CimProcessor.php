<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-processor
 */
class CimProcessor extends CimLogicalDevice
{
    protected $addressWidth;

    protected $currentClockSpeed;

    protected $dataWidth;

    protected $family;

    protected $loadPercentage;

    protected $maxClockSpeed;

    protected $otherFamilyDescription;

    protected $role;

    protected $stepping;

    protected $uniqueId;

    protected $upgradeMethod;

}
