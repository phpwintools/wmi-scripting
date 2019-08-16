<?php

namespace PhpWinTools\WmiScripting\Win32\Models;

use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Win32\Cim\CimDependency;
use PhpWinTools\WmiScripting\Query\LoggedOnUserBuilder;

/**
 * Class LoggedOnUser
 * @package App\Transformers\Com\Wmi\Win32\Providers
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-loggedonuser
 */
class LoggedOnUser extends CimDependency
{
    protected $uuid = '{8BB5B3EC-E1F7-4b39-942A-605D5F55789A}';

    protected $attribute_name_replacements = [
        'antecedent' => 'userAccount',
        'dependent' => 'logonSession',
    ];

    public static function query($connection = null)
    {
        return new LoggedOnUserBuilder($self = new static, $self->getConnection($connection));
    }
}
