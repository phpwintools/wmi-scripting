<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-cachememory
 */
class CimCacheMemory extends CimMemory
{
    protected $uuid = '{FAF76B65-798C-11D2-AAD1-006008C78BC7}';

    protected $associativity;

    protected $cacheType;

    protected $flushTimer;

    protected $level;

    protected $lineSize;

    protected $readPolicy;

    protected $replacementPolicy;

    protected $writePolicy;

}
