<?php

namespace PhpWinTools\WmiScripting\Win32\Cim;

/**
 * Class CimMediaAccessDevice
 * @package App\Transformers\Com\Wmi\Win32\Cim
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-mediaaccessdevice
 */
class CimMediaAccessDevice extends CimLogicalDevice
{
    protected $uuid = '{8502C52A-5FBB-11D2-AAC1-006008C78BC7}';

    protected $capabilities;

    protected $capabilityDescriptions;

    protected $compressionMethod;

    protected $defaultBlockSize;

    protected $errorMethodology;

    protected $maxBlockSize;

    protected $maxMediaSize;

    protected $minBlockSize;

    protected $needsCleaning;

    protected $numberOfMediaSupported;
}
