<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects;

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

    public function __construct(VariantWrapper $variant)
    {
        parent::__construct($variant);

        $this->name = $variant->Name;
        $this->value = $variant->Value;
    }
}
