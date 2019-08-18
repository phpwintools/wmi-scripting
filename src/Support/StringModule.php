<?php

namespace PhpWinTools\Support;

class StringModule
{
    public static function camel(string $string)
    {
        return lcfirst(self::studly($string));
    }

    public static function snake(string $string)
    {
        $string = preg_replace('/\s+/u', '', ucwords($string));

        return strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1_', $string));
    }

    public static function studly(string $string)
    {
        $string = ucwords(str_replace(['-', '_'], ' ', $string));

        return str_replace(' ', '', $string);
    }
}
