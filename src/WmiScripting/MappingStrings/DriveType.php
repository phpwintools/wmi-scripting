<?php

namespace PhpWinTools\WmiScripting\MappingStrings;

abstract class DriveType extends Mappings
{
    const STRING_ARRAY_CONSTANT_NAME = 'DRIVE_TYPES';

    const UNKNOWN           = 0;
    const NO_ROOT_DIRECTORY = 1;
    const REMOVABLE_DISK    = 2;
    const LOCAL_DISK        = 3;
    const NETWORK_DRIVE     = 4;
    const COMPACT_DISC      = 5;
    const RAM_DISK          = 6;

    const DRIVE_TYPES = [
        self::UNKNOWN => 'Unknown',
        self::NO_ROOT_DIRECTORY => 'No Root Directory',
        self::REMOVABLE_DISK => 'Removable Disk',
        self::LOCAL_DISK => 'Local Disk',
        self::NETWORK_DRIVE => 'Network Drive',
        self::COMPACT_DISC => 'Compact Disc',
        self::RAM_DISK => 'RAM Disk',
    ];
}
