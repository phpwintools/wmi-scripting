<?php

namespace PhpWinTools\WmiScripting\Support {

    use PhpWinTools\WmiScripting\Connection;
    use PhpWinTools\WmiScripting\Configuration\Config;
    use PhpWinTools\WmiScripting\Configuration\Resolver;
    use PhpWinTools\WmiScripting\Exceptions\InvalidConnectionException;

    /**
     * @param   Config|null $config
     *
     * @return  Config
     */
    function core(Config $config = null)
    {
        return $config ?? Config::instance();
    }

    /**
     * @param   Connection|string|null  $connection
     * @param   Connection|string|null  $default
     * @param   Config|null             $config
     *
     * @return  Connection|string|null
     */
    function connection($connection = null, $default = null, Config $config = null)
    {
        if (is_null($connection) && is_null($default)) {
            $connection = core($config)->getConnection();
        }

        if (is_string($connection) && trim($connection) !== '') {
            $connection = core($config)->getConnection($connection) ?? $connection;
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
     * @param   Config|null $config
     * @param   string|null $class
     * @param   mixed       $parameters
     *
     * @return  Resolver|mixed|null
     */
    function resolve(Config $config = null, string $class = null, ...$parameters)
    {
        return core($config)($class, $parameters);
    }
}
