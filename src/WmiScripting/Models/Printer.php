<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Cim\CimPrinter;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-printer
 */
class Printer extends CimPrinter
{
    protected $attributes;

    protected $averagePagesPerMinute;

    protected $comment;

    protected $default;

    protected $defaultPriority;

    protected $direct;

    protected $doCompleteFirst;

    protected $driverName;

    protected $enableBIDI;

    protected $enableDevQueryPrint;

    protected $extendedDetectedErrorState;

    protected $extendedPrinterStatus;

    protected $hidden;

    protected $keepPrintedJobs;

    protected $local;

    protected $location;

    protected $network;

    protected $parameters;

    protected $portName;

    protected $printerPaperNames;

    protected $printerState;

    protected $printJobDataType;

    protected $printProcessor;

    protected $priority;

    protected $published;

    protected $queued;

    protected $rawOnly;

    protected $separatorFile;

    protected $serverName;

    protected $shared;

    protected $shareName;

    protected $spoolEnabled;

    protected $startTime;

    protected $untilTime;

    protected $workOffline;
}
