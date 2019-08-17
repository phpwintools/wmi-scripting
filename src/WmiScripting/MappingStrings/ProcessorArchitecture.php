<?php

namespace PhpWinTools\WmiScripting\MappingStrings;

class ProcessorArchitecture extends Mappings
{
    const X86 = 0;
    const MIPS = 1;
    const ALPHA = 2;
    const POWER_PC = 3;
    const ARM = 5;
    const IA64 = 6;
    const X64 = 9;

    const STRING_ARRAY = [
        self::X86       => 'x86',
        self::MIPS      => 'MIPS',
        self::ALPHA     => 'Alpha',
        self::POWER_PC  => 'Power PC',
        self::ARM       => 'ARM',
        self::IA64      => 'ia64',
        self::X64       => 'x64',
    ];
}
