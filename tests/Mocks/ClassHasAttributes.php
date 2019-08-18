<?php

namespace Tests\Mocks;

use PhpWinTools\WmiScripting\Concerns\ComHasAttributes;

class ClassHasAttributes
{
    use ComHasAttributes;

    public $testProperty1 = 0;

    public $testProperty;

    public $replacedNameProperty;

    public $hiddenProperty = '';

    public function __construct()
    {
        /* Trait attributes cannot be redefined on class immediately implementing them */
        $this->attribute_name_replacements = [
            'replacedNameProperty' => 'new_name_property',
        ];
    }

    /* an attribute that gets changed before returning */
    public function getTestProperty1Attribute($value)
    {
        // replace underscores _ in string with spaces
        return str_replace('_', ' ', $value);
    }

    /* an attribute that only exists as a method aka calculated */
    public function getCalculatedTestValueAttribute()
    {
        // add 5 to the current testProperty
        return $this->getAttribute('testProperty1') + 5;
    }

    /* an attribute that does not have an associated property */
    public function getUnmappedAttributeModifiedAttribute($value)
    {
        // This upper-cases the given string and changes tense.
        return str_replace('SHOULD BE', 'AM', strtoupper($value));
    }
}
