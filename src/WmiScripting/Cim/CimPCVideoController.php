<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-pcvideocontroller
 */
class CimPCVideoController extends CimVideoController
{
    protected $uuid = '{1008CCE6-7BFF-11D2-AAD2-006008C78BC7}';

    protected $numberOfColorPlanes;

    protected $videoArchitecture;

    protected $videoMode;
}
