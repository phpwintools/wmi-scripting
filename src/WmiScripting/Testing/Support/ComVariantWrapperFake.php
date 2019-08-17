<?php

namespace PhpWinTools\WmiScripting\Testing\Support;

use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Support\ComVariantWrapper;

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
