<?php

namespace PhpWinTools\WmiScripting\Cim;

use PhpWinTools\WmiScripting\Models\Win32Model;
use PhpWinTools\WmiScripting\Query\ComponentBuilder;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-component
 */
class CimComponent extends Win32Model
{
    protected $uuid = '{8502C573-5FBB-11D2-AAC1-006008C78BC7}';

    /** @var CimManagedSystemElement */
    protected $groupComponent;

    /** @var CimManagedSystemElement */
    protected $partComponent;

    public static function query($connection = null)
    {
        return new ComponentBuilder($self = new static(), $self->getConnection($connection));
    }
}
