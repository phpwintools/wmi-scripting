<?php

namespace PhpWinTools\WmiScripting\Win32\Models;

use PhpWinTools\WmiScripting\Win32\Cim\CimCacheMemory;

class CacheMemory extends CimCacheMemory
{
    protected $uuid = '{FAF76B97-798C-11D2-AAD1-006008C78BC7}';

    protected $cacheSpeed;

    protected $currentSRAM;

    protected $errorCorrectType;

    protected $installedSize;

    protected $location;

    protected $maxCacheSize;

    protected $supportedSRAM;
}
