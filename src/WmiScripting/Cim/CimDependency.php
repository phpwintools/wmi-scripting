<?php

namespace PhpWinTools\WmiScripting\Cim;

use PhpWinTools\WmiScripting\Win32Model;
use PhpWinTools\WmiScripting\Query\DependencyBuilder;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-dependency
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
