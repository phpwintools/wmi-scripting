<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-pointingdevice
 */
class CimPointingDevice extends CimUserDevice
{
    protected $uuid = '{8502C535-5FBB-11D2-AAC1-006008C78BC7}';

    protected $handedness;

    protected $numberOfButtons;

    protected $pointingType;

    protected $resolution;
}
