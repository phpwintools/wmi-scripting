<?php

use PhpWinTools\Support\COM\Testing\COMFake;
use PhpWinTools\Support\COM\ComVariantWrapper;
use PhpWinTools\WmiScripting\Testing\Support\VARIANTFake;
use PhpWinTools\Support\COM\Testing\ComVariantWrapperFake;

return [
    'com' => [
        'com_class'              => COMFake::class,
        'variant_class'          => VARIANTFake::class,
        ComVariantWrapper::class => ComVariantWrapperFake::class,
    ],
];
