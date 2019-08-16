<?php

namespace PhpWinTools\WmiScripting\Win32\MappingStrings;

abstract class Mappings
{
    const STRING_ARRAY_CONSTANT_NAME = 'STRING_ARRAY';

    const STRING_ARRAY = [];

    public static function string(string $constant): string
    {
        $string_constant = static::STRING_ARRAY_CONSTANT_NAME;

        if (array_key_exists($constant, constant(get_called_class() . "::{$string_constant}"))) {
            return constant(get_called_class() . "::{$string_constant}")[$constant];
        }

        return '';
    }
}
