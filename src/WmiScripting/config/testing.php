<?php

use PhpWinTools\WmiScripting\Support\ComVariantWrapper;
use PhpWinTools\WmiScripting\Testing\Com\Support\COMFake;
use PhpWinTools\WmiScripting\Testing\Com\Support\VARIANTFake;
use PhpWinTools\WmiScripting\Testing\Com\Support\ComVariantWrapperFake;

return [
    'com' => [
        'com_class'              => COMFake::class,
        'variant_class'          => VARIANTFake::class,
        ComVariantWrapper::class => ComVariantWrapperFake::class,
    ]
];
