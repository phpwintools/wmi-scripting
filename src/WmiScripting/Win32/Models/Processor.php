<?php

namespace PhpWinTools\WmiScripting\Win32\Models;

use PhpWinTools\WmiScripting\Win32\Cim\CimProcessor;
use PhpWinTools\WmiScripting\Win32\MappingStrings\ProcessorArchitecture;

/**
 * Class Processor
 * @package App\Transformers\Com\Wmi\Win32\Providers
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-processor
 */
class Processor extends CimProcessor
{
    protected $uuid = '{8502C4BB-5FBB-11D2-AAC1-006008C78BC7}';

    protected $architecture;

    protected $assetTag;

    protected $characteristics;

    protected $cpuStatus;

    protected $currentVoltage;

    protected $extClock;

    protected $l2CacheSize;

    protected $l2CacheSpeed;

    protected $l3CacheSize;

    protected $l3CacheSpeed;

    protected $level;

    protected $manufacturer;

    protected $numberOfCores;

    protected $numberOfEnabledCore;

    protected $numberOfLogicalProcessors;

    protected $partNumber;

    protected $processorId;

    protected $processorType;

    protected $revision;

    protected $secondLevelAddressTranslationExtensions;

    protected $serialNumber;

    protected $socketDesignation;

    protected $threadCount;

    protected $version;

    protected $virtualizationFirmwareEnabled;

    protected $vMMonitorModeExtensions;

    protected $voltageCaps;

    public function __construct(array $attributes = [])
    {
        $this->attribute_casting['architecture'] = $this->constantToStringCallback(ProcessorArchitecture::class);

        parent::__construct($attributes);
    }

}
