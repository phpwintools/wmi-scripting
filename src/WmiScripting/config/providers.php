<?php

use PhpWinTools\WmiScripting\Support\Bus\CommandBus;
use PhpWinTools\WmiScripting\Support\Events\EventProvider;
use PhpWinTools\WmiScripting\Support\Events\EventHistoryProvider;

return [
    'bus'           => CommandBus::class,
    'event'         => EventProvider::class,
    'event_history' => EventHistoryProvider::class,
];
