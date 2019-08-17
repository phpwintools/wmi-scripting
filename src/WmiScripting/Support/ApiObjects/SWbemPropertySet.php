<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects;

use PhpWinTools\WmiScripting\Support\VariantWrapper;
use function PhpWinTools\WmiScripting\Support\resolve;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\Property;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\PropertySet;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/wmisdk/swbempropertyset
 */
class SWbemPropertySet extends AbstractWbemObject implements PropertySet
{
    /** @var array|SWbemProperty[] */
    protected $properties = [];

    protected $resolve_property_sets;

    public function __construct(VariantWrapper $variant, array $resolve_property_sets = [])
    {
        parent::__construct($variant);

        $this->resolve_property_sets = $resolve_property_sets;
        $this->buildProperties();
    }

    public function toArray(): array
    {
        $array = [];

        foreach ($this->properties as $key => $property) {
            if ($property instanceof SWbemObject) {
                $array[$key] = $property->getModel();
                continue;
            }

            $array[$key] = $property->toArray();
        }

        return $array;
    }

    protected function buildProperties()
    {
        foreach ($this->object as $item) {
            $this->properties[$item->Name] = $this->resolveProperty(
                resolve()->property($item),
                $item->Name
            );
        }
    }

    protected function resolveProperty(Property $property, $name)
    {
        if (!array_key_exists('services', $this->resolve_property_sets)
            || !array_key_exists('property_names', $this->resolve_property_sets)
        ) {
            return $property;
        }

        if (in_array($name, $this->resolve_property_sets['property_names'])) {
            return $this->resolve_property_sets['services']->get($property->getAttribute('value'));
        }

        return $property;
    }
}
