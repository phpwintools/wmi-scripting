<?php

namespace PhpWinTools\WmiScripting\Containers;

use PhpWinTools\WmiScripting\Connection;
use PhpWinTools\WmiScripting\Configuration\Config;

class Connections extends Container
{
    public function __construct(Config $config)
    {
        $items = [];

        foreach ($config->get('wmi.connections.servers') as $key => $value) {
            $items[$key] = new Connection(
                $value['server'] ?? Connection::DEFAULT_SERVER,
                $value['namespace'] ?? Connection::DEFAULT_NAMESPACE,
                $value['user'] ?? null,
                $value['password'] ?? null,
                $value['locale'] ?? null,
                $value['authority'] ?? null,
                $value['security_flags'] ?? null
            );
        }

        parent::__construct($items);
    }

    public function set($key, Connection $connection)
    {
        $this->offsetSet($key, $connection);

        return $this;
    }

    /**
     * @param string                $key
     * @param mixed|Connection|null $default
     *
     * @return Connection|null
     */
    public function get($key, $default = null)
    {
        return parent::get($key, $default); // TODO: Change the autogenerated stub
    }
}
