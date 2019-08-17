<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects;

use PhpWinTools\WmiScripting\Support\VariantWrapper;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\Property;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/wmisdk/swbemproperty
 */
class SWbemProperty extends AbstractWbemObject implements Property
{
    protected $name;

    protected $value;

    protected $origin;

    public function __construct(VariantWrapper $variant)
    {
        parent::__construct($variant);

        $this->name = $this->object->Name;
        $this->origin = $this->object->Origin;
        $this->value = $this->detectValue($this->object->Value);
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'origin' => $this->origin,
        ];
    }

    protected function detectValue($value)
    {
        if ($value instanceof VariantWrapper && $value->canIterateObject()) {
            $items = [];
            foreach ($value as $item) {
                $items[] = $item;
            }

            $value = $items;
        }

        return $value;
    }
}
