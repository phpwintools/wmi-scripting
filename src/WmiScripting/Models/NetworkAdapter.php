<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimNetworkAdapter;

class NetworkAdapter extends CimNetworkAdapter
{
    protected $uuid = '{8502C4C0-5FBB-11D2-AAC1-006008C78BC7}';

    protected $adapterType;

    protected $adapterTypeID;

    protected $gUID;

    protected $index;

    protected $installed;

    protected $interfaceIndex;

    protected $mACAddress;

    protected $manufacturer;

    protected $maxNumberControlled;

    protected $netConnectionID;

    protected $netConnectionStatus;

    protected $netEnabled;

    protected $physicalAdapter;

    protected $productName;

    protected $serviceName;

    protected $timeOfLastReset;

    public function enable()
    {
        //
    }

    public function disable()
    {
        //
    }
}
