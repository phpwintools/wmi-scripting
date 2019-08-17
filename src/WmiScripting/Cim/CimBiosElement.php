<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * Class CimBiosElement
 * @package App\Transformers\Com\Wmi\Win32\Cim
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-bioselement
 */
class CimBiosElement extends CimSoftwareElement
{
    protected $uuid = '{8502C562-5FBB-11D2-AAC1-006008C78BC7}';

    protected $primaryBIOS;
}
