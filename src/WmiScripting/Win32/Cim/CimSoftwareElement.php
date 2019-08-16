<?php

namespace PhpWinTools\WmiScripting\Win32\Cim;

use PhpWinTools\WmiScripting\Win32\MappingStrings\SoftwareElementState;

/**
 * Class CimSoftwareElement
 * @package App\Transformers\Com\Wmi\Win32\Cim
 * https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/cim-softwareelement
 */
class CimSoftwareElement extends CimLogicalElement
{
    protected $uuid = '{8502C561-5FBB-11D2-AAC1-006008C78BC7}';

    protected $buildNumber;

    protected $codeSet;

    protected $identificationCode;

    protected $languageEdition;

    protected $manufacturer;

    protected $otherTargetOS;

    protected $serialNumber;

    protected $softwareElementID;

    protected $softwareElementState;

    protected $targetOperatingSystem;

    protected $version;

    public function __construct(array $attributes = [])
    {
        $this->attribute_casting['softwareElementState'] = function ($value) {
            return SoftwareElementState::string($value);
        };

        parent::__construct($attributes);
    }
}
