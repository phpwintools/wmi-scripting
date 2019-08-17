<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-logicaldevice
 */
class CimLogicalDevice extends CimLogicalElement
{
    protected $uuid = '{8502C529-5FBB-11D2-AAC1-006008C78BC7}';

    protected $availability;

    protected $configManagerErrorCode;

    protected $configManagerUserConfig;

    protected $creationClassName;

    protected $deviceID;

    protected $errorCleared;

    protected $errorDescription;

    protected $lastErrorCode;

    protected $pNPDeviceID;

    protected $powerManagementCapabilities = [];

    protected $powerManagementSupported;

    protected $statusInfo;

    protected $systemCreationClassName;

    protected $systemName;

    protected $attribute_casting = [
        'systemName' => 'string',
    ];

    public function reset()
    {
        //
    }

    public function setPowerState()
    {
        //
    }

    public function getDeviceId()
    {
        return $this->getAttribute('deviceID');
    }
}
