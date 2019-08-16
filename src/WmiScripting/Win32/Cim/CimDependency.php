<?php

namespace PhpWinTools\WmiScripting\Win32\Cim;

use PhpWinTools\WmiScripting\Win32\Win32Model;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Query\DependencyBuilder;

/**
 * Class CimDependency
 * @package App\Transformers\Com\Wmi\Win32\Cim
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-dependency
 */
class CimDependency extends Win32Model
{
    protected $uuid = '{8502C53A-5FBB-11D2-AAC1-006008C78BC7}';

    protected $antecedent;

    protected $dependent;

    public static function query($connection = null)
    {
        return new DependencyBuilder($self = new static, $self->getConnection($connection));
    }
}