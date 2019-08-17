<?php

namespace PhpWinTools\Support\COM\Testing;

use PhpWinTools\Support\COM\ComVariantWrapper;
use PhpWinTools\WmiScripting\Configuration\Config;

class ComVariantWrapperFake extends ComVariantWrapper
{
    public function __construct(COMObjectFake $comObject, Config $config = null)
    {
        parent::__construct($comObject, $config);
    }

    public static function comToString(ComVariantWrapper $com)
    {
        return '';
    }

    /**
     * @return COMObjectFake
     */
    public function getComObject()
    {
        return parent::getComObject();
    }
}
