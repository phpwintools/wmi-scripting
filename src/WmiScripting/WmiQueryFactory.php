<?php

namespace PhpWinTools\WmiScripting;

use PhpWinTools\WmiScripting\Models\Bios;
use PhpWinTools\WmiScripting\Models\Group;
use PhpWinTools\WmiScripting\Models\Account;
use PhpWinTools\WmiScripting\Models\Process;
use PhpWinTools\WmiScripting\Models\DiskDrive;
use PhpWinTools\WmiScripting\Models\Processor;
use PhpWinTools\WmiScripting\Models\SystemBios;
use PhpWinTools\WmiScripting\Models\LogicalDisk;
use PhpWinTools\WmiScripting\Models\UserAccount;
use PhpWinTools\WmiScripting\Models\LoggedOnUser;
use PhpWinTools\WmiScripting\Models\SystemDevices;
use PhpWinTools\WmiScripting\Models\ComputerSystem;
use PhpWinTools\WmiScripting\Models\OperatingSystem;
use PhpWinTools\WmiScripting\Models\TemperatureProbe;
use PhpWinTools\WmiScripting\Connections\ComConnection;
use PhpWinTools\WmiScripting\Models\ProgramGroupContents;

class WmiQueryFactory
{
    /** @var ComConnection|string|null */
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
