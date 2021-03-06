<?php

namespace PhpWinTools\WmiScripting\Models;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-memorydevice
 */
class MemoryDevice extends SMBIOSMemory
{
    protected $uuid = '{FAF76B9B-798C-11D2-AAD1-006008C78BC7}';

    protected $errorGranularity;
}
