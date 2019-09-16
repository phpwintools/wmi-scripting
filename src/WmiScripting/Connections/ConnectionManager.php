<?php

namespace PhpWinTools\WmiScripting\Connections;

class ConnectionManager
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
}
