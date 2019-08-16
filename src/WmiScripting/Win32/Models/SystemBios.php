<?php

namespace PhpWinTools\WmiScripting\Win32\Models;

use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Query\SystemBiosBuilder;
use PhpWinTools\WmiScripting\Win32\Cim\CimSystemComponent;

/**
 * Class SystemBios
 * @package App\Transformers\Com\Wmi\Win32\Providers
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-systembios
 */
class SystemBios extends CimSystemComponent
{
    protected $uuid = '{8502C4EE-5FBB-11D2-AAC1-006008C78BC7}';

    protected $attribute_name_replacements = [
        'partComponent' => 'Win32_BIOS',
        'groupComponent' => 'Win32_ComputerSystem',
    ];

    public static function query($connection = null)
    {
        return new SystemBiosBuilder($self = new static, $self->getConnection($connection));
    }
}
