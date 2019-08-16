<?php

namespace PhpWinTools\WmiScripting\Win32\Models;

use PhpWinTools\WmiScripting\Win32\Cim\CimLogicalElement;
use PhpWinTools\WmiScripting\Win32\MappingStrings\SidType;

/**
 * Class Account
 * @package App\Transformers\Com\Wmi\Win32\Providers
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-account
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
