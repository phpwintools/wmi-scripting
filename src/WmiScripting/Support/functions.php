<?php

namespace PhpWinTools\WmiScripting\Support {

    use ReflectionClass;
    use PhpWinTools\WmiScripting\Configuration\Config;
    use PhpWinTools\WmiScripting\Configuration\Resolver;
    use PhpWinTools\WmiScripting\Connections\ComConnection;
    use PhpWinTools\WmiScripting\Collections\ArrayCollection;
    use PhpWinTools\WmiScripting\Exceptions\InvalidArgumentException;
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
     * @param ComConnection|string|null $connection
     * @param ComConnection|string|null $default
     * @param Config|null               $config
     *
     * @return ComConnection|string|null
     */
    function connection($connection = null, $default = null, Config $config = null)
    {
        if (is_null($connection) && is_null($default)) {
            return core($config)->getConnection();
        }

        if (is_string($connection) && trim($connection) !== '') {
            $connection = core($config)->getConnection($connection);
        }

        if (!$connection instanceof ComConnection && $default) {
            return connection($default, null, core($config));
        }

        if (!$connection instanceof ComConnection) {
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

    /**
     * Checks for the existence of a property.
     *
     * @param $class
     * @param $property_name
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    function class_has_property($class, $property_name): bool
    {
        $class = is_object($class) ? get_class($class) : $class;

        try {
            return (new ReflectionClass($class))->hasProperty($property_name);
        } catch (\Exception $exception) {
            throw new InvalidConnectionException("Unable to reflect on {$class}.", null, $exception);
        }
    }

    /**
     * Gets the default value of the given property through the parent.
     *
     * @param $class
     * @param $property_name
     *
     * @return array
     */
    function get_ancestor_property($class, $property_name)
    {
        $class = is_object($class) ? get_class($class) : $class;

        return ArrayCollection::collect(class_parents($class))->map(function ($class) use ($property_name) {
            return (new ReflectionClass($class))->getDefaultProperties()[$property_name] ?? [];
        })->values()->collapse()->toArray();
    }

    /**
     * Expects either a class name or instance and will return if the given trait exists on the class.
     *
     * @param $class
     * @param $trait
     *
     * @return bool
     */
    function class_has_trait($class, $trait): bool
    {
        return array_key_exists($trait, class_traits(is_object($class) ? get_class($class) : $class));
    }

    /**
     * Returns all traits of the given class or instance. It also checks the traits for any used traits.
     *
     * @param $class
     *
     * @return array
     */
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

    /**
     * Returns all of the traits that a trait uses including it's ancestors.
     *
     * @param $trait_name
     *
     * @return array
     */
    function trait_traits($trait_name)
    {
        $traits = class_uses($trait_name);

        foreach ($traits as $trait) {
            $traits += trait_traits($trait);
        }

        return $traits;
    }
}
