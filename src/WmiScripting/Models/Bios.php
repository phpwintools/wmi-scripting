<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimBiosElement;
use PhpWinTools\WmiScripting\MappingStrings\BiosCharacteristics;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-bios
 */
class Bios extends CimBiosElement
{
    protected $uuid = '{8502C4E1-5FBB-11D2-AAC1-006008C78BC7}';

    protected $biosCharacteristics;

    protected $bIOSVersion;

    protected $currentLanguage;

    protected $embeddedControllerMajorVersion;

    protected $embeddedControllerMinorVersion;

    protected $installableLanguages;

    protected $listOfLanguages;

    protected $releaseDate;

    protected $sMBIOSBIOSVersion;

    protected $sMBIOSMajorVersion;

    protected $sMBIOSMinorVersion;

    protected $sMBIOSPresent;

    protected $systemBiosMajorVersion;

    protected $systemBiosMinorVersion;

    public function __construct(array $attributes = [])
    {
        $this->attribute_casting['biosCharacteristics'] = function ($values) {
            $characteristics = [];

            foreach ($values as $constant) {
                if (trim($characteristic = BiosCharacteristics::string($constant)) === '') {
                    $characteristics[$constant] = 'UNKNOWN';
                    continue;
                }

                $characteristics[$constant] = $characteristic;
            }

            return $characteristics;
        };

        parent::__construct($attributes);
    }
}
