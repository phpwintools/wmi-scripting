<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects;

use PhpWinTools\WmiScripting\Support\VariantWrapper;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\QualifierSet;
use function PhpWinTools\WmiScripting\Support\resolve;

/**
 * Class WbemQualifierSet
 * @package App\Transformers\Com\Wmi
 * https://docs.microsoft.com/en-us/windows/win32/wmisdk/swbemqualifierset
 */
class SWbemQualifierSet extends AbstractWbemObject implements QualifierSet
{
    protected $count = 0;

    protected $qualifiers = [];

    public function __construct(VariantWrapper $variant)
    {
        parent::__construct($variant);

        $this->buildQualifiers();
        $this->count = $variant->Count;
    }

    protected function buildQualifiers()
    {
        foreach ($this->object as $item) {
            $this->qualifiers[$item->Name] = resolve()->qualifier($item);
        }
    }
}
