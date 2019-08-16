<?php

namespace PhpWinTools\WmiScripting\Flags;

abstract class WbemFlags
{
    /** ExecQuery Flags: https://docs.microsoft.com/en-us/windows/win32/wmisdk/swbemservices-execquery */
    const BIDIRECTIONAL             = 0x0;
    const FORWARD_ONLY              = 0x20;
    const QUERY_FLAG_PROTOTYPE      = 0x2;
    const RETURN_IMMEDIATELY        = 0x10;
    const RETURN_WHEN_COMPLETE      = 0x0;
    const USE_AMENDED_QUALIFIERS    = 0x20000;
}
