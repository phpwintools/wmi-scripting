<?php

namespace PhpWinTools\WmiScripting\Support {

    use PhpWinTools\WmiScripting\Connection;
    use PhpWinTools\WmiScripting\Configuration\Config;
    use PhpWinTools\WmiScripting\Configuration\Resolver;
    use PhpWinTools\WmiScripting\Exceptions\InvalidConnectionException;

    /**
     * @param Config|null $config
     *
     * @return Config
     */
    function core(Config $config = null)
    {
        return $config ?? Config::instance();
    }

    /**
     * @param Connection|string|null $connection
     * @param Connection|string|null $default
     * @param Config|null            $config
     *
     * @return Connection|string|null
     */
    function connection($connection = null, $default = null, Config $config = null)
    {
        if (is_null($connection) && is_null($default)) {
            return core($config)->getConnection();
        }

        if (is_string($connection) && trim($connection) !== '') {
            $connection = core($config)->getConnection($connection);
        }

        if (!$connection instanceof Connection && $default) {
            return connection($default, null, core($config));
        }

        if (!$connection instanceof Connection) {
            throw InvalidConnectionException::new($connection);
        }

        return $connection;
    }

    /**
     * @param string|null $class
     * @param mixed       $parameters
     *
     * @return Resolver|mixed|null
     */
    function resolve(string $class = null, ...$parameters)
    {
        return core()($class, $parameters);
    }

    function class_traits($class)
    {
        $class = is_object($class) ? get_class($class) : $class;

        $traits = class_uses($class);

        while ($class = get_parent_class($class)) {
            $traits += class_uses($class);
        }

        $trait_traits = [];

        foreach ($traits as $trait) {
            $trait_traits += trait_traits($trait);
        }

        return array_merge($trait_traits, $traits);
    }

    function trait_traits($trait)
    {
        $traits = class_uses($trait);

        foreach ($traits as $trait) {
            $traits += trait_traits($trait);
        }

        return $traits;
    }
}
