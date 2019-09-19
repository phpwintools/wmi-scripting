<?php

use PhpWinTools\Support\COM\ComWrapper;
use PhpWinTools\Support\COM\VariantWrapper;
use PhpWinTools\Support\COM\ComVariantWrapper;
use PhpWinTools\WmiScripting\Support\Cache\ArrayDriver;
use PhpWinTools\WmiScripting\Support\Events\EventCache;

return [
    'cache' => [
        'driver' => ArrayDriver::class,
    ],

    'com' => [
        'com_class'                 => COM::class,
        'variant_class'             => VARIANT::class,
        ComVariantWrapper::class    => ComVariantWrapper::class,
        ComWrapper::class           => ComWrapper::class,
        VariantWrapper::class       => VariantWrapper::class,
    ],

    'event' => [
        'track'  => false,
        'cache'  => [
            'driver' => EventCache::class,
        ]
    ],

    'providers' => include('providers.php'),

    'wmi' => include('wmi.php'),
];
