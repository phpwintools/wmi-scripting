<?php

namespace PhpWinTools\WmiScripting\Win32\MappingStrings;

class SidType extends Mappings
{
    const USER = 1;
    const GROUP = 2;
    const DOMAIN = 3;
    const ALIAS = 4;
    const WELL_KNOWN_GROUP = 5;
    const DELETED_ACCOUNT = 6;
    const INVALID = 7;
    const UNKNOWN = 8;
    const COMPUTER = 9;

    const STRING_ARRAY = [
        self::USER => 'User',
        self::GROUP => 'Group',
        self::DOMAIN => 'Domain',
        self::ALIAS => 'Alias',
        self::WELL_KNOWN_GROUP => 'Well Known Group',
        self::DELETED_ACCOUNT => 'Deleted Account',
        self::INVALID => 'Invalid',
        self::UNKNOWN => 'Unknown',
        self::COMPUTER => 'Computer',
    ];
}
