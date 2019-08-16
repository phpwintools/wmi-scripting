<?php

namespace PhpWinTools\WmiScripting\Win32\Cim;

use PhpWinTools\WmiScripting\Win32\Win32Model;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Query\ComponentBuilder;

/**
 * Class CimComponent
 * @package App\Transformers\Com\Wmi\Win32\Cim
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-component
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
        return new ComponentBuilder($self = new static, $self->getConnection($connection));
    }
}
