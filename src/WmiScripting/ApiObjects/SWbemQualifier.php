<?php

namespace PhpWinTools\WmiScripting\ApiObjects;

use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Support\VariantWrapper;

/**
 * Class WbemQualifier
 * @package App\Transformers\Com\Wmi
 *
 */
class SWbemQualifier extends AbstractWbemObject
{
    protected $name;

    protected $value;

    public function __construct(VariantWrapper $variant, Config $config = null)
    {
        parent::__construct($variant, $config);

        $this->name = $variant->Name;
        $this->value = $variant->Value;
    }
}
