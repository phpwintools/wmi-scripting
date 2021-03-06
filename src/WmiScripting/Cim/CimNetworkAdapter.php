<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-networkadapter
 */
class CimNetworkAdapter extends CimLogicalDevice
{
    protected $uuid = '{8502C532-5FBB-11D2-AAC1-006008C78BC7}';

    protected $autoSense;

    protected $maxSpeed;

    protected $networkAddresses;

    protected $permanentAddress;

    protected $speed;
}
