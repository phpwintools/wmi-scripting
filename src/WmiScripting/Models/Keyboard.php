<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimKeyboard;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-keyboard
 */
class Keyboard extends CimKeyboard
{
    protected $uuid = '{8502C4B5-5FBB-11D2-AAC1-006008C78BC7}';
}
