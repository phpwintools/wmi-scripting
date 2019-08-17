<?php

namespace PhpWinTools\WmiScripting\Models;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-logicalprogramgroup
 */
class LogicalProgramGroup extends ProgramGroupOrItem
{
    protected $uuid = '{D52706F2-8045-11d2-90CE-0060081A46FD}';

    protected $groupName;

    protected $userName;
}
