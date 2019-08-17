<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects;

use PhpWinTools\Support\COM\VariantWrapper;
use function PhpWinTools\WmiScripting\Support\resolve;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\QualifierSet;
use PhpWinTools\WmiScripting\Support\ApiObjects\VariantInterfaces\QualifierVariant;
use PhpWinTools\WmiScripting\Support\ApiObjects\VariantInterfaces\QualifierSetVariant;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/wmisdk/swbemqualifierset
 */
class SWbemQualifierSet extends AbstractWbemObject implements QualifierSet
{
    protected $count = 0;

    protected $qualifiers = [];

    /** @var VariantWrapper|QualifierSetVariant|QualifierVariant[] */
    protected $object;

    public function __construct(VariantWrapper $variant)
    {
        parent::__construct($variant);

        $this->buildQualifiers();
        $this->count = (int) $this->object->Count;
    }

    protected function buildQualifiers()
    {
        foreach ($this->object as $item) {
            $this->qualifiers[$item->Name] = resolve()->qualifier($item);
        }
    }
}
