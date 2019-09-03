<?php

namespace PhpWinTools\WmiScripting\Concerns;

use Exception;
use ReflectionClass;
use PhpWinTools\Support\StringModule;
use PhpWinTools\Support\BooleanModule;
use PhpWinTools\WmiScripting\Contracts\Arrayable;
use PhpWinTools\WmiScripting\Collections\ArrayCollection;
use Illuminate\Contracts\Support\Arrayable as IlluminateArrayable;

trait HasArrayableAttributes
{
    protected $casts_booted = false;

    protected $unmapped_attributes = [];

    protected $attribute_name_replacements = [];

    public function getAttribute($attribute, $default = null)
    {
        if ($this->hasAttributeMethod($attribute)) {
            return $this->getAttributeMethodValue('get' . StringModule::studly($attribute) . 'Attribute', $attribute);
        }

        $value = $default;

        if ($this->hasProperty($attribute)) {
            $value = $this->{$attribute};
        }

        if ($key = array_search($attribute, $this->attribute_name_replacements)) {
            $value = $this->{$key};
        }

        if (array_key_exists($attribute, $this->unmapped_attributes)) {
            $value = $this->unmapped_attributes[$attribute];
        }

        if ($this->hasCast($attribute)) {
            $value = $this->cast($attribute, $value);
        }

        return $value;
    }

    public function toArray(): array
    {
        return array_merge(
            $this->getCalculatedAttributes(),
            $this->iterateAttributes(get_class_vars(get_called_class())),
            $this->iterateAttributes($this->unmapped_attributes)
        );
    }

    public function collect(array $array)
    {
        return new ArrayCollection($array);
    }

    public function setUnmappedAttribute($key, $value)
    {
        $this->unmapped_attributes[$key] = $value;

        return $this;
    }

    public function getCasts(): array
    {
        if (!$this->casts_booted) {
            $this->bootCasts();
        }

        return $this->attribute_casting;
    }

    public function getCast($attribute)
    {
        $casts = $this->getCasts();

        return $casts[$attribute] ?? null;
    }

    public function hasCast($attribute): bool
    {
        return array_key_exists($attribute, $this->getCasts());
    }

    protected function bootCasts()
    {
        $merge_casting = true;
        $attribute_casting = [];

        if ($this->hasProperty('merge_parent_casting')) {
            $merge_casting = $this->merge_parent_casting;
        }

        if ($this->hasProperty('attribute_casting')) {
            $attribute_casting = $this->attribute_casting;
        }

        $this->attribute_casting = $merge_casting
            ? array_merge($this->getAncestorProperty('attribute_casting'), $attribute_casting)
            : $attribute_casting;

        $this->casts_booted = true;
    }

    protected function getAttributeMethodValue($method, $attribute)
    {
        if ($this->hasProperty($attribute)) {
            return $this->{$method}($this->{$attribute});
        }

        if ($this->hasUnmappedAttribute($attribute)) {
            return  $this->{$method}($this->unmapped_attributes[$attribute]);
        }

        return $this->{$method}($attribute);
    }

    protected function getCalculatedAttributes()
    {
        return $this->collect($this->calculatedAttributes())->mapWithKeys(function ($attribute) {
            return [$attribute => $this->{'get' . lcfirst($attribute) . 'Attribute'}()];
        })->toArray();
    }

    protected function calculatedAttributes()
    {
        return $this->collect($this->getAttributeMethods())->map(function ($method) {
            return $this->getAttributeNameFromMethod($method);
        })->filter(function ($attribute) {
            return !$this->hasProperty($attribute) && !$this->hasUnmappedAttribute($attribute);
        })->values()->toArray();
    }

    protected function hasUnmappedAttribute($attribute)
    {
        return array_key_exists($attribute, $this->unmapped_attributes);
    }

    protected function hasAttributeMethod($attribute)
    {
        return $this->collect($this->getAttributeMethods())->filter(function ($method) use ($attribute) {
            if (($method_name = $this->getAttributeNameFromMethod($method)) === $attribute) {
                return true;
            }

            $method_name = StringModule::snake($method_name);

            if ($method_name === $attribute) {
                return true;
            }

            return false;
        })->isNotEmpty();
    }

    protected function getAttributeNameFromMethod($method)
    {
        return lcfirst(substr($method, 3, -9));
    }

    protected function getAttributeMethods()
    {
        return $this->collect(get_class_methods(get_called_class()))->filter(function ($method) {
            return substr($method, 0, 3) === 'get' && substr($method, -9) === 'Attribute' && $method !== 'getAttribute';
        })->values()->toArray();
    }

    public function hasProperty($property_name)
    {
        return array_key_exists($property_name, get_class_vars(get_called_class()));
    }

    protected function replaceAttributeName($key)
    {
        if (array_key_exists($key, $this->attribute_name_replacements)) {
            return $this->attribute_name_replacements[$key];
        }

        return $key;
    }

    protected function cast($key, $value)
    {
        $casts = $this->getCasts();

        if (!$this->hasCast($key)) {
            return $value;
        }

        /* @TODO: This isn't needed. Need to write tests around it so I can remove it. */
        if (is_callable($casts[$key])) {
            return $casts[$key]($value, $key);
        }

        switch ($casts[$key]) {
            case 'array':
                return is_array($value) ? $value : [$value];
            case 'bool':
            case 'boolean':
                return BooleanModule::makeBoolean($value);
            case 'int':
            case 'integer':
                // Prevent integer overflow
                return $value >= PHP_INT_MAX || $value <= PHP_INT_MIN ? (string) $value : (int) $value;
            case 'string':
                if ($value === true) {
                    return 'true';
                }

                if ($value === false) {
                    return 'false';
                }

                if (is_array($value)) {
                    return json_encode($value);
                }

                return (string) $value;
            default:
                return $value;
        }
    }

    protected function objectToArray($value)
    {
        if (!is_object($value)) {
            return $value;
        }

        $array = (array) $value;

        foreach ($array as $key => $value) {
            if (strpos($key, "\x00*\x00") === 0) {
                $array[substr($key, 3)] = $value;
                unset($array[$key]);
            }
        }

        return $array;
    }

    protected function tryToArrayMethod($value)
    {
        if (!is_object($value)) {
            return $value;
        }

        if (is_object($value) && $this->hasToArrayMethod($value)) {
            try {
                $array = $value->toArray();
                if (is_array($array)) {
                    return $array;
                }
            } catch (Exception $exception) {
                return $value;
            }
        }

        return $value;
    }

    protected function hasToArrayMethod($value)
    {
        if (!is_object($value)) {
            return false;
        }

        if (in_array('toArray', get_class_methods($value))) {
            return true;
        }

        return false;
    }

    protected function transformToArray($value)
    {
        if (is_object($value) && ($value instanceof Arrayable || $value instanceof IlluminateArrayable)) {
            return $value->toArray();
        }

        if (is_object($value) && is_array(($array = $this->tryToArrayMethod($value)))) {
            return $array;
        }

        if (is_object($value)) {
            return $this->objectToArray($value);
        }

        if (is_array($value)) {
            return $this->iterateAttributes($value);
        }

        return $value;
    }

    protected function iterateAttributes(array $attributes)
    {
        $results = [];

        foreach ($attributes as $key => $value) {
            if ($this->isHidden($key)) {
                continue;
            }

            $results[$this->replaceAttributeName($key)] = $this->transformToArray($this->getAttribute($key, $value));
        }

        return $results;
    }

    protected function getAncestorProperty($property_name)
    {
        return $this->collect(class_parents($this))->map(function ($class) use ($property_name) {
            return (new ReflectionClass($class))->getDefaultProperties()[$property_name] ?? [];
        })->values()->collapse()->toArray();
    }
}
