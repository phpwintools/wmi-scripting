<?php

namespace Tests\Mocks;

use PhpWinTools\WmiScripting\Concerns\ComHasAttributes;

class ClassHasAttributes
{
    use ComHasAttributes;

    public $testProperty1;

    public $testProperty;

    public function getTestProperty1Attribute($value)
    {
        // replace underscores _ in string with spaces
        return str_replace('_', ' ', $value);
    }

    public function getCalculatedTestValueAttribute()
    {
        // add 5 to the current testProperty
        return $this->getAttribute('testProperty1') + 5;
    }

    public function getUnmappedAttributeModifiedAttribute($value)
    {
        // This upper-cases the given string and changes tense.
        return str_replace('SHOULD BE', 'AM', strtoupper($value));
    }
}
