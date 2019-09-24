<?php

use PhpWinTools\WmiScripting\Connections\ConnectionManager;
use PhpWinTools\WmiScripting\Support\Bus\CommandBus;
use PhpWinTools\WmiScripting\Support\Cache\CacheProvider;
use PhpWinTools\WmiScripting\Support\Events\EventProvider;
use PhpWinTools\WmiScripting\Support\Events\EventHistoryProvider;

return [
    'concrete' => [
        'bus'           => CommandBus::class,
        'cache'         => CacheProvider::class,
        'connection'    => ConnectionManager::class,
        'event'         => EventProvider::class,
        'event_history' => EventHistoryProvider::class,
    ],

    'aliases' => [
        CommandBus::class           => 'bus',
        CacheProvider::class        => 'cache',
        ConnectionManager::class    => 'connection',
        EventProvider::class        => 'event',
        EventHistoryProvider::class => 'event_history',
    ],
];
