<?php

namespace PhpWinTools\WmiScripting\Concerns;

use PhpWinTools\Support\BooleanModule;
use function PhpWinTools\WmiScripting\Support\class_has_property;
use function PhpWinTools\WmiScripting\Support\get_ancestor_property;

trait HasCastableAttributes
{
    protected $casts_booted = false;

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

    protected function cast($key, $value)
    {
        $casts = $this->getCasts();

        if (!$this->hasCast($key)) {
            return $value;
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

    protected function bootCasts()
    {
        $merge_casting = true;
        $attribute_casting = [];

        if (class_has_property(get_called_class(), 'merge_parent_casting')) {
            $merge_casting = $this->merge_parent_casting;
        }

        if (class_has_property(get_called_class(), 'attribute_casting')) {
            $attribute_casting = $this->attribute_casting;
        }

        $this->attribute_casting = $merge_casting
            ? array_merge(get_ancestor_property(get_called_class(), 'attribute_casting'), $attribute_casting)
            : $attribute_casting;

        $this->casts_booted = true;
    }
}
