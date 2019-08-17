<?php

namespace PhpWinTools\WmiScripting\Models;

/**
 * Class LogonSession
 * @package App\Transformers\Com\Wmi\Win32\Providers
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-logonsession
 */
class LogonSession extends Session
{
    protected $uuid = '{9083C21E-7D58-4e0e-BC30-0BC8922AFB8B}';

    protected $authenticationPackage;

    protected $logonId;

    protected $logonType;
}
