<?php

use PhpWinTools\WmiScripting\Connections\ComConnection;

return [
    'default' => 'local',

    'servers' => [
        'local' => [
            'type'            => ComConnection::class,
            'server'          => '.',
            'namespace'       => 'Root\CIMv2',
            'user'            => null,
            'password'        => null,
            'locale'          => null,
            'authority'       => null,
            'security_flags'  => null,
        ],
    ],
];
