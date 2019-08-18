<?php

namespace PhpWinTools\WmiScripting\Concerns;

use Exception;
use Illuminate\Support\Str;
use ReflectionClass;
use PhpWinTools\WmiScripting\Contracts\Arrayable;
use Illuminate\Contracts\Support\Arrayable as IlluminateArrayable;

trait ComHasAttributes
{
    use CanCollect;

    protected $unmapped_attributes = [];

    protected $attribute_name_replacements = [];

    protected $trait_name_replacements = [];

    protected $trait_hidden_attributes = [
        'trait_hidden_attributes',
        'trait_name_replacements',

        'attribute_name_replacements',
        'unmapped_attributes',

        'hidden_attributes',
        'merge_parent_hidden_attributes',

        'attribute_casting',
        'merge_parent_casting',
    ];

    public function getAttribute($attribute, $default = null)
    {
        if ($this->hasAttributeMethod($attribute)) {
            $method = 'get' . ucfirst($this->snackCaseToCamel($attribute)) . 'Attribute';
            if ($this->hasProperty($attribute)) {
                return $this->{$method}($this->{$attribute});
            } elseif ($this->hasUnmappedAttribute($attribute)) {
                return  $this->{$method}($this->unmapped_attributes[$attribute]);
            }

            return $this->{$method}();
        }

        if ($this->hasProperty($attribute)) {
            return $this->{$attribute};
        }

        if ($key = array_search($attribute, $this->attribute_name_replacements)) {
            return $this->{$key};
        }

        if (array_key_exists($attribute, $this->unmapped_attributes)) {
            return $this->unmapped_attributes[$attribute];
        }

        return $default;
    }

    public function snackCaseToCamel(string $string)
    {
        $string = ucwords(str_replace(['-', '_'], ' ', $string));

        return lcfirst(str_replace(' ', '', $string));
    }

    public function camelCaseToSnack(string $string)
    {
        $string = preg_replace('/\s+/u', '', ucwords($string));

        return strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1_', $string));
    }

    public function toArray(): array
    {
        return array_merge(
            $this->getCalculatedAttributes(),
            $this->iterateAttributes(get_class_vars(get_called_class())),
            $this->iterateAttributes($this->unmapped_attributes)
        );
    }

    public function setHidden(array $hidden_attributes, bool $merge_hidden = true)
    {
        $hidden_attributes = $merge_hidden
            ? array_merge($this->getAncestorProperty('hidden_attributes'), $hidden_attributes)
            : $hidden_attributes;

        $this->trait_hidden_attributes = array_merge($this->trait_hidden_attributes, $hidden_attributes);

        return $this;
    }

    public function isHidden($key): bool
    {
        $this->trait_hidden_attributes = array_combine($this->trait_hidden_attributes, $this->trait_hidden_attributes);

        return array_key_exists($key, $this->trait_hidden_attributes);
    }

    public function getHidden()
    {
        return array_combine($this->trait_hidden_attributes, $this->trait_hidden_attributes);
    }

    public function setUnmappedAttribute($key, $value)
    {
        $this->unmapped_attributes[$key] = $value;

        return $this;
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

            $method_name = $this->camelCaseToSnack($method_name);

            if ($method_name  === $attribute) {
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

    protected function setAttributeNameReplacements(array $name_replacements)
    {
        $this->trait_name_replacements = $name_replacements;

        return $this;
    }

    protected function hasProperty($property_name)
    {
        return array_key_exists($property_name, get_class_vars(get_called_class()));
    }

    protected function replaceAttributeName($key)
    {
        if (array_key_exists($key, $this->trait_name_replacements)) {
            return $this->trait_name_replacements[$key];
        }

        return $key;
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

    public function getCasts(): array
    {
        return $this->attribute_casting;
    }

    public function getCast($attribute)
    {
        $casts = $this->getCasts();

        return $casts[$attribute] ?? null;
    }

    public function hasCast($attribute): bool
    {
        return array_key_exists($attribute, $this->attribute_casting);
    }

    public function setCasts(array $attribute_casting, bool $merge_casting = true)
    {
        $this->attribute_casting = $merge_casting
            ? array_merge($this->getAncestorProperty('attribute_casting'), $attribute_casting)
            : $attribute_casting;
    }

    protected function cast($key, $value)
    {
        $casts = $this->getCasts();

        if (!$this->hasCast($key)) {
            return $value;
        }

        if (is_callable($casts[$key])) {
            return $casts[$key]($value, $key);
        }

        switch ($casts[$key]) {
            case 'array':
                return is_array($value) ? $value : [$value];
            case 'bool':
            case 'boolean':
                return (bool) $value;
            case 'float':
                return (float) $value;
            case 'int':
            case 'integer':
                // Prevent integer overflow
                return $value >= PHP_INT_MAX || $value <= PHP_INT_MIN ? (string) $value : (int) $value;
            case 'string':
                return (string) $value;
            default:
                return $value;
        }
    }

    protected function getAncestorProperty($property_name)
    {
        return $this->collect(class_parents($this))->map(function ($class) use ($property_name) {
            return (new ReflectionClass($class))->getDefaultProperties()[$property_name] ?? [];
        })->values()->collapse()->toArray();
    }
}
