<?php

namespace PhpWinTools\WmiScripting\Win32\Cim;

/**
 * Class CimSystem
 * @package App\Transformers\Com\Wmi\Win32\Cim
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-system
 */
class CimSystem extends CimLogicalElement
{
    protected $uuid = '{8502C524-5FBB-11D2-AAC1-006008C78BC7}';

    protected $creationClassName;

    protected $nameFormat;

    protected $primaryOwnerContact;

    protected $primaryOwnerName;

    protected $roles;
}
