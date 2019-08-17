<?php

namespace PhpWinTools\WmiScripting\MappingStrings;

class AccountType extends Mappings
{
    const UF_TEMP_DUPLICATE_ACCOUNT     = 256;
    const UF_NORMAL_ACCOUNT             = 512;
    const UF_INTERDOMAIN_TRUST_ACCOUNT  = 2048;
    const UF_WORKSTATION_TRUST_ACCOUNT  = 4096;
    const UF_SERVER_TRUST_ACCOUNT       = 8192;

    const STRING_ARRAY = [
        self::UF_TEMP_DUPLICATE_ACCOUNT     => 'Duplicate Account',
        self::UF_NORMAL_ACCOUNT             => 'Normal Account',
        self::UF_INTERDOMAIN_TRUST_ACCOUNT  => 'Interdomain Trust Account',
        self::UF_WORKSTATION_TRUST_ACCOUNT  => 'Workstation Trust Account',
        self::UF_SERVER_TRUST_ACCOUNT       => 'Server Trust Account',
    ];
}
