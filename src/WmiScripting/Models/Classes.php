<?php

namespace PhpWinTools\WmiScripting\Models;

abstract class Classes
{
    /**
     * Computer System Hardware Classes.
     *
     * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/computer-system-hardware-classes
     */
    const BIOS = 'Win32_BIOS';
    const BUS = 'Win32_Bus';
    const CACHE_MEMORY = 'Win32_CacheMemory';
    const COMPUTER_SYSTEM_PROCESSOR = 'Win32_ComputerSystemProcessor';
    const CURRENT_PROBE = 'Win32_CurrentProbe';
    const DESKTOP_MONITOR = 'Win32_DesktopMonitor';
    const DISK_DRIVE = 'Win32_DiskDrive';
    const DISK_PARTITION = 'Win32_DiskPartition';
    const FAN = 'Win32_Fan';
    const IDE_CONTROLLER = 'Win32_IDEController';
    const KEYBOARD = 'Win32_Keyboard';
    const LOGICAL_DISK = 'Win32_LogicalDisk';
    const LOGICAL_PROGRAM_GROUP = 'Win32_LogicalProgramGroup';
    const LOGICAL_PROGRAM_GROUP_ITEM = 'Win32_LogicalProgramGroupItem';
    const MAPPED_LOGICAL_DISK = 'Win32_MappedLogicalDisk';
    const MEMORY_ARRAY = 'Win32_MemoryArray';
    const MEMORY_DEVICE = 'Win32_MemoryDevice';
    const MOTHERBOARD_DEVICE = 'Win32_MotherboardDevice';
    const NETWORK_ADAPTER = 'Win32_NetworkAdapter';
    const PNP_ENTITY = 'Win32_PnPEntity';
    const POINTING_DEVICE = 'Win32_PointingDevice';
    const PRINTER = 'Win32_Printer';
    const PROCESSOR = 'Win32_Processor';
    const SCSI_CONTROLLER = 'Win32_SCSIController';
    const SERIAL_PORT = 'Win32_SerialPort';
    const SMBIOS_MEMORY = 'Win32_SMBIOSMemory';
    const SOUND_DEVICE = 'Win32_SoundDevice';
    const TEMPERATURE_PROBE = 'Win32_TemperatureProbe';
    const USB_CONTROLLER = 'Win32_USBController';
    const USB_HUB = 'Win32_USBHub';
    const VIDEO_CONTROLLER = 'Win32_VideoController';
    const VOLTAGE_SENSOR = 'Win32_VoltageProbe';
    const VOLUME = 'Win32_Volume';

    /**
     * Operating System Classes.
     *
     * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/operating-system-classes
     */
    const ACCOUNT = 'Win32_Account';
    const COMPUTER_SYSTEM = 'Win32_ComputerSystem';
    const GROUP = 'Win32_Group';
    const LOGGED_ON_USER = 'Win32_LoggedOnUser';
    const LOGON_SESSION = 'Win32_LogonSession';
    const OPERATING_SYSTEM = 'Win32_OperatingSystem';
    const PROCESS = 'Win32_Process';
    const PROGRAM_GROUP_CONTENTS = 'Win32_ProgramGroupContents';
    const PROGRAM_GROUP_OR_ITEM = 'Win32_ProgramGroupOrItem';
    const SYSTEM_BIOS = 'Win32_SystemBIOS';
    const SYSTEM_DEVICES = 'Win32_SystemDevices';
    const SYSTEM_PARTITIONS = 'Win32_SystemPartitions';
    const USER_ACCOUNT = 'Win32_UserAccount';

    const CLASS_MAP = [
        /* Computer System Hardware Classes */
        self::BIOS                          => Bios::class,
        self::BUS                           => Bus::class,
        self::CACHE_MEMORY                  => CacheMemory::class,
        self::COMPUTER_SYSTEM_PROCESSOR     => ComputerSystemProcessor::class,
        self::CURRENT_PROBE                 => CurrentProbe::class,
        self::DESKTOP_MONITOR               => DesktopMonitor::class,
        self::DISK_DRIVE                    => DiskDrive::class,
        self::DISK_PARTITION                => DiskPartition::class,
        self::FAN                           => Fan::class,
        self::IDE_CONTROLLER                => IDEController::class,
        self::KEYBOARD                      => Keyboard::class,
        self::LOGICAL_DISK                  => LogicalDisk::class,
        self::LOGICAL_PROGRAM_GROUP         => LogicalProgramGroup::class,
        self::LOGICAL_PROGRAM_GROUP_ITEM    => LogicalProgramGroupItem::class,
        self::MAPPED_LOGICAL_DISK           => MappedLogicalDisk::class,
        self::MEMORY_DEVICE                 => MemoryDevice::class,
        self::MEMORY_ARRAY                  => MemoryArray::class,
        self::MOTHERBOARD_DEVICE            => MotherboardDevice::class,
        self::NETWORK_ADAPTER               => NetworkAdapter::class,
        self::PNP_ENTITY                    => PnPEntity::class,
        self::POINTING_DEVICE               => PointingDevice::class,
        self::PRINTER                       => Printer::class,
        self::PROCESSOR                     => Processor::class,
        self::SCSI_CONTROLLER               => SCSIController::class,
        self::SERIAL_PORT                   => SerialPort::class,
        self::SMBIOS_MEMORY                 => SMBIOSMemory::class,
        self::SOUND_DEVICE                  => SoundDevice::class,
        self::TEMPERATURE_PROBE             => TemperatureProbe::class,
        self::USB_CONTROLLER                => USBController::class,
        self::USB_HUB                       => USBHub::class,
        self::VIDEO_CONTROLLER              => VideoController::class,
        self::VOLTAGE_SENSOR                => VoltageProbe::class,
        self::VOLUME                        => Volume::class,

        /* Operating System Classes */
        self::ACCOUNT                       => Account::class,
        self::COMPUTER_SYSTEM               => ComputerSystem::class,
        self::GROUP                         => Group::class,
        self::LOGGED_ON_USER                => LoggedOnUser::class,
        self::LOGON_SESSION                 => LogonSession::class,
        self::PROCESS                       => Process::class,
        self::PROGRAM_GROUP_CONTENTS        => ProgramGroupContents::class,
        self::PROGRAM_GROUP_OR_ITEM         => ProgramGroupOrItem::class,
        self::OPERATING_SYSTEM              => OperatingSystem::class,
        self::SYSTEM_BIOS                   => SystemBios::class,
        self::SYSTEM_DEVICES                => SystemDevices::class,
        self::SYSTEM_PARTITIONS             => SystemPartitions::class,
        self::USER_ACCOUNT                  => UserAccount::class,
    ];

    public static function win32ClassFromPHPClass(string $class_name)
    {
        $win32_class = array_search($class_name, self::CLASS_MAP);

        if ($win32_class === false) {
            return Win32Model::class;
        }

        return $win32_class;
    }

    public static function getClass(string $classes_constant)
    {
        if (array_key_exists($classes_constant, self::CLASS_MAP)) {
            return self::CLASS_MAP[$classes_constant];
        }

        return Win32Model::class;
    }
}
