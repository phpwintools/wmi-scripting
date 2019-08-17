<?php

namespace PhpWinTools\WmiScripting\Cim;

class CimNumericSensor extends CimSensor
{
    protected $uuid = '{9565979C-7D80-11D2-AAD3-006008C78BC7}';

    protected $accuracy;

    protected $currentReading;

    protected $isLinear;

    protected $lowerThresholdCritical;

    protected $lowerThresholdFatal;

    protected $lowerThresholdNonCritical;

    protected $maxReadable;

    protected $minReadable;

    protected $nominalReading;

    protected $normalMax;

    protected $normalMin;

    protected $resolution;

    protected $tolerance;

    protected $upperThresholdCritical;

    protected $upperThresholdFatal;

    protected $upperThresholdNonCritical;

}
