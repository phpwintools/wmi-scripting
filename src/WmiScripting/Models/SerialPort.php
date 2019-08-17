<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimSerialController;

/**
 * Class SerialPort
 * @package App\Transformers\Com\Wmi\Win32\Providers
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-serialport
 */
class SerialPort extends CimSerialController
{
    protected $uuid = '{8502C4BF-5FBB-11D2-AAC1-006008C78BC7}';

    protected $binary;

    protected $maximumInputBufferSize;

    protected $maximumOutputBufferSize;

    protected $oSAutoDiscovered;

    protected $providerType;

    protected $settableBaudRate;

    protected $settableDataBits;

    protected $settableFlowControl;

    protected $settableParity;

    protected $settableParityCheck;

    protected $settableRLSD;

    protected $settableStopBits;

    protected $supports16BitMode;

    protected $supportsDTRDSR;

    protected $supportsElapsedTimeouts;

    protected $supportsIntTimeouts;

    protected $supportsParityCheck;

    protected $supportsRLSD;

    protected $supportsRTSCTS;

    protected $supportsSpecialCharacters;

    protected $supportsXOnXOff;

    protected $supportsXOnXOffSet;

}

