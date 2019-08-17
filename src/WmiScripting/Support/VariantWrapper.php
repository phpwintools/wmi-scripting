<?php

namespace PhpWinTools\WmiScripting\Support;

use VARIANT as VariantExt;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Testing\Support\VARIANTFake;
use PhpWinTools\WmiScripting\Exceptions\VariantWrapperException;

class VariantWrapper extends ComVariantWrapper
{
    public function __construct($variant, Config $config = null)
    {
        if (!$variant instanceof VariantExt && !$variant instanceof VARIANTFake) {
            throw new VariantWrapperException('Must be an instance of VARIANT or VARIANTFake. Given: '
                . get_class($variant));
        }

        parent::__construct($variant, $config);
    }
}
