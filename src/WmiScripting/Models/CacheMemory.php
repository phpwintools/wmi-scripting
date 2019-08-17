<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimCacheMemory;

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
