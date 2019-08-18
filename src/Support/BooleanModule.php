<?php

namespace PhpWinTools\Support;

class BooleanModule
{
    public static function makeBoolean($value): bool
    {
        $value = strtolower($value);

        if ($value === true || $value === false) {
            return $value;
        }

        if ($value === 'true') {
            return true;
        }

        if ($value === 'false') {
            return false;
        }

        if ($value === 1 || $value === '1') {
            return true;
        }

        if ($value === 0 || $value === '0') {
            return false;
        }

        if (is_array($value)) {
            return !empty($value);
        }

        if (trim($value) !== '') {
            return true;
        } else {
            return false;
        }
    }
}
