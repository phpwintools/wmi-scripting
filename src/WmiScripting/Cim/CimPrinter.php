<?php

namespace PhpWinTools\WmiScripting\Cim;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-printer
 */
class CimPrinter extends CimLogicalDevice
{
    protected $uuid = '{8502C54A-5FBB-11D2-AAC1-006008C78BC7}';

    protected $availableJobSheets;

    protected $capabilities;

    protected $capabilityDescriptions;

    protected $charSetsSupported;

    protected $currentCapabilities;

    protected $currentCharSet;

    protected $currentLanguage;

    protected $currentMimeType;

    protected $currentNaturalLanguage;

    protected $currentPaperType;

    protected $defaultCapabilities;

    protected $defaultCopies;

    protected $defaultLanguage;

    protected $defaultMimeType;

    protected $defaultNumberUp;

    protected $defaultPaperType;

    protected $detectedErrorState;

    protected $errorInformation;

    protected $horizontalResolution;

    protected $jobCountSinceLastReset;

    protected $languagesSupported;

    protected $markingTechnology;

    protected $maxCopies;

    protected $maxNumberUp;

    protected $maxSizeSupported;

    protected $mimeTypesSupported;

    protected $naturalLanguagesSupported;

    protected $paperSizesSupported;

    protected $paperTypesAvailable;

    protected $printerStatus;

    protected $timeOfLastReset;

    protected $verticalResolution;
}
