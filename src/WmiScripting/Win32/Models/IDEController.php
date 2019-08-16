<?php

namespace PhpWinTools\WmiScripting\Win32\Models;

use PhpWinTools\WmiScripting\Win32\Cim\CimController;

/**
 * Class IDEController
 * @package App\Transformers\Com\Wmi\Win32\Providers
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-idecontroller
 */
class IDEController extends CimController
{
    protected $uuid = '{9ABA5122-C7A1-11d2-911D-0060081A46FD}';

    protected $manufacturer;
}
