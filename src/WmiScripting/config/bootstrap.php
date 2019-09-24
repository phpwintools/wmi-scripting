<?php

use PhpWinTools\Support\COM\ComWrapper;
use PhpWinTools\Support\COM\VariantWrapper;
use PhpWinTools\Support\COM\ComVariantWrapper;
use PhpWinTools\WmiScripting\Connections\ComConnection;
use PhpWinTools\WmiScripting\Support\Cache\ArrayDriver;

return [
    'cache' => [
        'driver' => ArrayDriver::class,
        'ttl' => 60 * 5,
    ],

    'com' => [
        'com_class'                 => COM::class,
        'variant_class'             => VARIANT::class,
        ComVariantWrapper::class    => ComVariantWrapper::class,
        ComWrapper::class           => ComWrapper::class,
        VariantWrapper::class       => VariantWrapper::class,
    ],

    'connection' => [
        'default_driver' => ComConnection::class,
    ],

    'event' => [
        'track'  => false,
    ],

    'providers' => include('providers.php'),

    'wmi' => include('wmi.php'),
];
