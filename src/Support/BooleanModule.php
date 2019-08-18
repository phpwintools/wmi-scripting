<?php

namespace PhpWinTools\Support;

class BooleanModule
{
    public static function makeBoolean($value): bool
    {
        if ($value === true || $value === false) {
            return $value;
        }

        if (is_array($value)) {
            return !empty($value);
        }

        if ($value === 1 || $value === '1') {
            return true;
        }

        if ($value === 0 || $value === '0') {
            return false;
        }

        $value = strtolower($value);

        if ($value === 'true') {
            return true;
        }

        if ($value === 'false') {
            return false;
        }

        if (trim($value) !== '') {
            return true;
        } else {
            return false;
        }
    }
}
