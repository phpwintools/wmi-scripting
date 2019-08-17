<?php

use PhpWinTools\WmiScripting\Testing\Support\COMFake;
use PhpWinTools\WmiScripting\Support\ComVariantWrapper;
use PhpWinTools\WmiScripting\Testing\Support\VARIANTFake;
use PhpWinTools\WmiScripting\Testing\Support\ComVariantWrapperFake;

return [
    'com' => [
        'com_class'              => COMFake::class,
        'variant_class'          => VARIANTFake::class,
        ComVariantWrapper::class => ComVariantWrapperFake::class,
    ],
];
