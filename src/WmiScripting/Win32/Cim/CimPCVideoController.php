<?php

namespace PhpWinTools\WmiScripting\Win32\Cim;

/**
 * Class CimPCVideoController
 * @package App\Transformers\Com\Wmi\Win32\Cim
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-pcvideocontroller
 */
class CimPCVideoController extends CimVideoController
{
    protected $uuid = '{1008CCE6-7BFF-11D2-AAD2-006008C78BC7}';

    protected $numberOfColorPlanes;

    protected $videoArchitecture;

    protected $videoMode;
}
