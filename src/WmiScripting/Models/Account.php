<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimLogicalElement;
use PhpWinTools\WmiScripting\MappingStrings\SidType;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-account
 */
class Account extends CimLogicalElement
{
    protected $uuid = '{8502C4C9-5FBB-11D2-AAC1-006008C78BC7}';

    protected $domain;

    protected $localAccount;

    protected $sID;

    protected $sIDType;

    public function __construct(array $attributes = [])
    {
        $this->attribute_casting['sIDType'] = $this->constantToStringCallback(SidType::class);

        parent::__construct($attributes);
    }
}
