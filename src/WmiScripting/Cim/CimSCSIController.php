<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * Class CimSCSIController
 * @package App\Transformers\Com\Wmi\Win32\Cim
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-scsicontroller
 */
class CimSCSIController extends CimController
{
    protected $uuid = '{8502C553-5FBB-11D2-AAC1-006008C78BC7}';

    protected $controllerTimeouts;

    protected $maxDataWidth;

    protected $maxTransferRate;

    protected $protectionManagement;
}
