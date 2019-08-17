<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimDiskDrive;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-diskdrive
 */
class DiskDrive extends CimDiskDrive
{
    protected $uuid = '{8502C4B2-5FBB-11D2-AAC1-006008C78BC7}';

    protected $bytesPerSector;

    protected $firmwareRevision;

    protected $index;

    protected $interfaceType;

    protected $manufacturer;

    protected $mediaLoaded;

    protected $mediaType;

    protected $model;

    protected $partitions;

    protected $sCSIBus;

    protected $sCSILogicalUnit;

    protected $sCSIPort;

    protected $sCSITargetId;

    protected $sectorsPerTrack;

    protected $serialNumber;

    protected $signature;

    protected $size;

    protected $totalCylinders;

    protected $totalHeads;

    protected $totalSectors;

    protected $totalTracks;

    protected $tracksPerCylinder;
}
