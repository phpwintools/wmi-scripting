<?php

namespace PhpWinTools\WmiScripting\Cim;

use PhpWinTools\WmiScripting\Win32Model;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-managedsystemelement
 */
class CimManagedSystemElement extends Win32Model
{
    protected $uuid = '{8502C517-5FBB-11D2-AAC1-006008C78BC7}';

    protected $caption;

    protected $description;

    protected $installDate;

    protected $name;

    protected $status;
}
