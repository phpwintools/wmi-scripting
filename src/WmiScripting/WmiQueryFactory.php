<?php

namespace PhpWinTools\WmiScripting;

use PhpWinTools\WmiScripting\Win32\Models\Bios;
use PhpWinTools\WmiScripting\Win32\Models\Group;
use PhpWinTools\WmiScripting\Win32\Models\Account;
use PhpWinTools\WmiScripting\Win32\Models\Process;
use PhpWinTools\WmiScripting\Win32\Models\DiskDrive;
use PhpWinTools\WmiScripting\Win32\Models\Processor;
use PhpWinTools\WmiScripting\Win32\Models\SystemBios;
use PhpWinTools\WmiScripting\Win32\Models\LogicalDisk;
use PhpWinTools\WmiScripting\Win32\Models\UserAccount;
use PhpWinTools\WmiScripting\Win32\Models\LoggedOnUser;
use PhpWinTools\WmiScripting\Win32\Models\SystemDevices;
use PhpWinTools\WmiScripting\Win32\Models\ComputerSystem;
use PhpWinTools\WmiScripting\Win32\Models\OperatingSystem;
use PhpWinTools\WmiScripting\Win32\Models\TemperatureProbe;
use PhpWinTools\WmiScripting\Win32\Models\ProgramGroupContents;

class WmiQueryFactory
{
    protected $connection;

    public function __construct($connection = null)
    {
        $this->connection = $connection;
    }

    public function bios()
    {
        return Bios::query($this->connection);
    }

    public function diskDrive()
    {
        return DiskDrive::query($this->connection);
    }

    public function logicalDisk()
    {
        return LogicalDisk::query($this->connection);
    }

    public function processor()
    {
        return Processor::query($this->connection);
    }

    public function temperatureProbe()
    {
        return TemperatureProbe::query($this->connection);
    }

    public function account()
    {
        return Account::query($this->connection);
    }

    public function computerSystem()
    {
        return ComputerSystem::query($this->connection);
    }

    public function group()
    {
        return Group::query($this->connection);
    }

    public function loggedOnUser()
    {
        return LoggedOnUser::query($this->connection);
    }

    public function operatingSystem()
    {
        return OperatingSystem::query($this->connection);
    }

    public function process()
    {
        return Process::query($this->connection);
    }

    public function programGroupContents()
    {
        return ProgramGroupContents::query($this->connection);
    }

    public function systemBios()
    {
        return SystemBios::query($this->connection);
    }

    public function systemDevices()
    {
        return SystemDevices::query($this->connection);
    }

    public function userAccount()
    {
        return UserAccount::query($this->connection);
    }
}
