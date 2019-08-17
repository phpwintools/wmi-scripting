<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects;

use PhpWinTools\WmiScripting\Support\VariantWrapper;
use PhpWinTools\WmiScripting\Support\ApiObjects\VariantInterfaces\QualifierVariant;

class SWbemQualifier extends AbstractWbemObject
{
    protected $name;

    protected $value;

    /** @var VariantWrapper|QualifierVariant */
    protected $object;

    public function __construct(VariantWrapper $variant)
    {
        parent::__construct($variant);

        $this->name = (string) $this->object->Name;
        $this->value = $this->object->Value;
    }
}
