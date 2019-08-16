<?php

namespace PhpWinTools\WmiScripting\Win32\Models;

use PhpWinTools\WmiScripting\Win32\Cim\CimKeyboard;

/**
 * Class Keyboard
 * @package App\Transformers\Com\Wmi\Win32\Providers
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-keyboard
 */
class Keyboard extends CimKeyboard
{
    protected $uuid = '{8502C4B5-5FBB-11D2-AAC1-006008C78BC7}';
}
