<?php

namespace PhpWinTools\WmiScripting\Models;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-memoryarray
 */
class MemoryArray extends SMBIOSMemory
{
    protected $uuid = '{FAF76B9A-798C-11D2-AAD1-006008C78BC7}';

    protected $errorGranularity;
}
