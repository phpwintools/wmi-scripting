<?php

namespace PhpWinTools\WmiScripting\ApiObjects;

use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Support\VariantWrapper;
use PhpWinTools\WmiScripting\Contracts\ApiObjects\Property;

/**
 * Class SWbemProperty
 * @package App\Transformers\Com\Wmi
 * https://docs.microsoft.com/en-us/windows/win32/wmisdk/swbemproperty
 */
class SWbemProperty extends AbstractWbemObject implements Property
{
    protected $name;

    protected $value;

    protected $origin;

    public function __construct(VariantWrapper $variant, Config $config = null)
    {
        parent::__construct($variant, $config);

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
