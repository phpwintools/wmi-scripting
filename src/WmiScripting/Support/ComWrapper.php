<?php

namespace PhpWinTools\WmiScripting\Support;

use COM as ComExt;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Testing\Support\COMFake;
use PhpWinTools\WmiScripting\Exceptions\ComWrapperException;

class ComWrapper extends ComVariantWrapper
{
    public function __construct($com, Config $config = null)
    {
        if (!$com instanceof Comext && !$com instanceof COMFake) {
            throw new ComWrapperException('Must be an instance of COM or COMFake Given: '
                . get_class($com));
        }

        parent::__construct($com, $config);
    }
}
