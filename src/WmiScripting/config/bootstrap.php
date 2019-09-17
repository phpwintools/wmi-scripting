<?php

use PhpWinTools\Support\COM\ComWrapper;
use PhpWinTools\Support\COM\VariantWrapper;
use PhpWinTools\Support\COM\ComVariantWrapper;
use PhpWinTools\WmiScripting\Support\Events\EventHandler;

return [
    'com' => [
        'com_class'                 => COM::class,
        'variant_class'             => VARIANT::class,
        ComVariantWrapper::class    => ComVariantWrapper::class,
        ComWrapper::class           => ComWrapper::class,
        VariantWrapper::class       => VariantWrapper::class,
    ],

    'events' => [
        'handler' => EventHandler::class,
        'track'   => false,
    ],

    'wmi' => include('wmi.php'),
];
