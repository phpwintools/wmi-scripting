<?php

namespace PhpWinTools\WmiScripting\Win32\Cim;

/**
 * Class CimKeyboard
 * @package App\Transformers\Com\Wmi\Win32\Cim
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-keyboard
 */
class CimKeyboard extends CimUserDevice
{
    protected $uuid = '{8502C534-5FBB-11D2-AAC1-006008C78BC7}';

    protected $layout;

    protected $numberOfFunctionKeys;

    protected $password;
}
