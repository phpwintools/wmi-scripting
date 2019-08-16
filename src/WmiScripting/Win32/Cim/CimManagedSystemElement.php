<?php

namespace PhpWinTools\WmiScripting\Win32\Cim;

use PhpWinTools\WmiScripting\Win32\Win32Model;

/**
 * Class ManagedSystemElement
 * @package App\Transformers\Com\Wmi\Win32\Cim
 *
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-managedsystemelement
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
