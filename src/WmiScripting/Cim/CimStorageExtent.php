<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-storageextent
 */
class CimStorageExtent extends CimLogicalDevice
{
    protected $uuid = '{8502C538-5FBB-11D2-AAC1-006008C78BC7}';

    protected $access;

    protected $blockSize;

    protected $errorMethodology;

    protected $numberOfBlocks;

    protected $purpose;
}
