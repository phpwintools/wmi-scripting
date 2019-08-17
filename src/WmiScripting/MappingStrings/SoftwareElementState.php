<?php

namespace PhpWinTools\WmiScripting\MappingStrings;

class SoftwareElementState extends Mappings
{
    const DEPLOYABLE = 0;
    const INSTALLABLE = 1;
    const EXECUTABLE = 2;
    const RUNNING = 3;

    const STRING_ARRAY = [
        self::DEPLOYABLE  => 'Deployable',
        self::INSTALLABLE => 'Installable',
        self::EXECUTABLE  => 'Executable',
        self::RUNNING     => 'Running',
    ];
}
