<?php

use PhpWinTools\Support\COM\ComWrapper;
use PhpWinTools\Support\COM\VariantWrapper;
use PhpWinTools\Support\COM\ComVariantWrapper;
use PhpWinTools\WmiScripting\Support\Events\Bus;

return [
    'bus' => [
        'class' => Bus::class,
        'listeners' => [],
    ],

    'com' => [
        'com_class'                 => COM::class,
        'variant_class'             => VARIANT::class,
        ComVariantWrapper::class    => ComVariantWrapper::class,
        ComWrapper::class           => ComWrapper::class,
        VariantWrapper::class       => VariantWrapper::class,
    ],

    'wmi' => include('wmi.php'),
];
