<?php

namespace PhpWinTools\WmiScripting\MappingStrings;

class BiosCharacteristics extends Mappings
{
    const RESERVED = 0;
    const RESERVED_ = 1;
    const UNKNOWN = 2;
    const BIOS_CHARACTERISTICS_NOT_SUPPORTED = 3;
    const ISA_IS_SUPPORTED = 4;
    const MCA_IS_SUPPORTED = 5;
    const EISA_IS_SUPPORTED = 6;
    const PCI_IS_SUPPORTED = 7;
    const PC_CARD_PCMCIA_IS_SUPPORTED = 8;
    const PLUG_AND_PLAY_IS_SUPPORTED = 9;
    const APM_IS_SUPPORTED = 10;
    const BIOS_IS_UPGRADEABLE_FLASH = 11;
    const BIOS_SHADOWING_IS_ALLOWED = 12;
    const VL_VESA_IS_SUPPORTED = 13;
    const ESCD_SUPPORT_IS_AVAILABLE = 14;
    const BOOT_FROM_CD_IS_SUPPORTED = 15;
    const SELECTABLE_BOOT_IS_SUPPORTED = 16;
    const BIOS_ROM_IS_SOCKETED = 17;
    const BOOT_FROM_PC_CARD_PCMCIA_IS_SUPPORTED = 18;
    const EDD_ENHANCED_DISK_DRIVE_SPECIFICATION_IS_SUPPORTED = 19;
    const INT_13H_JAPANESE_FLOPPY_FOR_NEC_9800_1_2MB_3_5_1K_BYTES_SECTOR_360_RPM_IS_SUPPORTED = 20;
    const INT_13H_JAPANESE_FLOPPY_FOR_TOSHIBA_1_2MB_3_5_360_RPM_IS_SUPPORTED = 21;
    const INT_13H_5_25__360_KB_FLOPPY_SERVICES_ARE_SUPPORTED = 22;
    const INT_13H_5_25_1_2MB_FLOPPY_SERVICES_ARE_SUPPORTED = 23;
    const INT_13H_3_5__720_KB_FLOPPY_SERVICES_ARE_SUPPORTED = 24;
    const INT_13H_3_5__2_88_MB_FLOPPY_SERVICES_ARE_SUPPORTED = 25;
    const INT_5H_PRINT_SCREEN_SERVICE_IS_SUPPORTED = 26;
    const INT_9H_8042_KEYBOARD_SERVICES_ARE_SUPPORTED = 27;
    const INT_14H_SERIAL_SERVICES_ARE_SUPPORTED = 28;
    const INT_17H_PRINTER_SERVICES_ARE_SUPPORTED = 29;
    const INT_10H_CGA_MONO_VIDEO_SERVICES_ARE_SUPPORTED = 30;
    const NEC_PC98 = 31;
    const ACPI_SUPPORTED = 32;
    const USB_LEGACY_IS_SUPPORTED = 33;
    const AGP_IS_SUPPORTED = 34;
    const I2O_BOOT_IS_SUPPORTED = 35;
    const LS120_BOOT_IS_SUPPORTED = 36;
    const ATAPI_ZIP_DRIVE_BOOT_IS_SUPPORTED = 37;
    const _1394_BOOT_IS_SUPPORTED = 38;
    const SMART_BATTERY_SUPPORTED = 39;

    const STRING_ARRAY = [
        self::RESERVED => 'RESERVED',
        self::RESERVED_ => 'RESERVED_',
        self::UNKNOWN => 'UNKNOWN',
        self::BIOS_CHARACTERISTICS_NOT_SUPPORTED => 'BIOS_CHARACTERISTICS_NOT_SUPPORTED',
        self::ISA_IS_SUPPORTED => 'ISA_IS_SUPPORTED',
        self::MCA_IS_SUPPORTED => 'MCA_IS_SUPPORTED',
        self::EISA_IS_SUPPORTED => 'EISA_IS_SUPPORTED',
        self::PCI_IS_SUPPORTED => 'PCI_IS_SUPPORTED',
        self::PC_CARD_PCMCIA_IS_SUPPORTED => 'PC_CARD_PCMCIA_IS_SUPPORTED',
        self::PLUG_AND_PLAY_IS_SUPPORTED => 'PLUG_AND_PLAY_IS_SUPPORTED',
        self::APM_IS_SUPPORTED => 'APM_IS_SUPPORTED',
        self::BIOS_IS_UPGRADEABLE_FLASH => 'BIOS_IS_UPGRADEABLE_FLASH',
        self::BIOS_SHADOWING_IS_ALLOWED => 'BIOS_SHADOWING_IS_ALLOWED',
        self::VL_VESA_IS_SUPPORTED => 'VL_VESA_IS_SUPPORTED',
        self::ESCD_SUPPORT_IS_AVAILABLE => 'ESCD_SUPPORT_IS_AVAILABLE',
        self::BOOT_FROM_CD_IS_SUPPORTED => 'BOOT_FROM_CD_IS_SUPPORTED',
        self::SELECTABLE_BOOT_IS_SUPPORTED => 'SELECTABLE_BOOT_IS_SUPPORTED',
        self::BIOS_ROM_IS_SOCKETED => 'BIOS_ROM_IS_SOCKETED',
        self::BOOT_FROM_PC_CARD_PCMCIA_IS_SUPPORTED => 'BOOT_FROM_PC_CARD_PCMCIA_IS_SUPPORTED',
        self::EDD_ENHANCED_DISK_DRIVE_SPECIFICATION_IS_SUPPORTED
            => 'EDD_ENHANCED_DISK_DRIVE_SPECIFICATION_IS_SUPPORTED',
        self::INT_13H_JAPANESE_FLOPPY_FOR_NEC_9800_1_2MB_3_5_1K_BYTES_SECTOR_360_RPM_IS_SUPPORTED
            => 'INT_13H_JAPANESE_FLOPPY_FOR_NEC_9800_1_2MB_3_5_1K_BYTES_SECTOR_360_RPM_IS_SUPPORTED',
        self::INT_13H_JAPANESE_FLOPPY_FOR_TOSHIBA_1_2MB_3_5_360_RPM_IS_SUPPORTED
            => 'INT_13H_JAPANESE_FLOPPY_FOR_TOSHIBA_1_2MB_3_5_360_RPM_IS_SUPPORTED',
        self::INT_13H_5_25__360_KB_FLOPPY_SERVICES_ARE_SUPPORTED
            => 'INT_13H_5_25__360_KB_FLOPPY_SERVICES_ARE_SUPPORTED',
        self::INT_13H_5_25_1_2MB_FLOPPY_SERVICES_ARE_SUPPORTED => 'INT_13H_5_25_1_2MB_FLOPPY_SERVICES_ARE_SUPPORTED',
        self::INT_13H_3_5__720_KB_FLOPPY_SERVICES_ARE_SUPPORTED => 'INT_13H_3_5__720_KB_FLOPPY_SERVICES_ARE_SUPPORTED',
        self::INT_13H_3_5__2_88_MB_FLOPPY_SERVICES_ARE_SUPPORTED
            => 'INT_13H_3_5__2_88_MB_FLOPPY_SERVICES_ARE_SUPPORTED',
        self::INT_5H_PRINT_SCREEN_SERVICE_IS_SUPPORTED => 'INT_5H_PRINT_SCREEN_SERVICE_IS_SUPPORTED',
        self::INT_9H_8042_KEYBOARD_SERVICES_ARE_SUPPORTED => 'INT_9H_8042_KEYBOARD_SERVICES_ARE_SUPPORTED',
        self::INT_14H_SERIAL_SERVICES_ARE_SUPPORTED => 'INT_14H_SERIAL_SERVICES_ARE_SUPPORTED',
        self::INT_17H_PRINTER_SERVICES_ARE_SUPPORTED => 'INT_17H_PRINTER_SERVICES_ARE_SUPPORTED',
        self::INT_10H_CGA_MONO_VIDEO_SERVICES_ARE_SUPPORTED => 'INT_10H_CGA_MONO_VIDEO_SERVICES_ARE_SUPPORTED',
        self::NEC_PC98 => 'NEC_PC98',
        self::ACPI_SUPPORTED => 'ACPI_SUPPORTED',
        self::USB_LEGACY_IS_SUPPORTED => 'USB_LEGACY_IS_SUPPORTED',
        self::AGP_IS_SUPPORTED => 'AGP_IS_SUPPORTED',
        self::I2O_BOOT_IS_SUPPORTED => 'I2O_BOOT_IS_SUPPORTED',
        self::LS120_BOOT_IS_SUPPORTED => 'LS120_BOOT_IS_SUPPORTED',
        self::ATAPI_ZIP_DRIVE_BOOT_IS_SUPPORTED => 'ATAPI_ZIP_DRIVE_BOOT_IS_SUPPORTED',
        self::_1394_BOOT_IS_SUPPORTED => '_1394_BOOT_IS_SUPPORTED',
        self::SMART_BATTERY_SUPPORTED => 'SMART_BATTERY_SUPPORTED',
        40 => 'SMART_BATTERY_SUPPORTED',
        47 => 'SMART_BATTERY_SUPPORTED',
        48 => 'RESERVED_FOR_BIOS_VENDOR',
        63 => 'RESERVED_FOR_BIOS_VENDOR',
    ];
}
