<?php

namespace PhpWinTools\WmiScripting\Support {

    use Closure;
    use ReflectionClass;
    use PhpWinTools\WmiScripting\Configuration\Config;
    use PhpWinTools\WmiScripting\Configuration\Resolver;
    use PhpWinTools\WmiScripting\Connections\ComConnection;
    use PhpWinTools\WmiScripting\Collections\ArrayCollection;
    use PhpWinTools\WmiScripting\Exceptions\InvalidArgumentException;
    use PhpWinTools\WmiScripting\Exceptions\InvalidConnectionException;

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
            throw new InvalidArgumentException("Unable to reflect on {$class}.", null, $exception);
        }
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
     * @param Config|null $config
     *
     * @return Config
     */
    function core(Config $config = null)
    {
        return $config ?? Config::instance();
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
     * Simple null check and replace only when $value is not null. Created to make string concatenations
     * a little cleaner to avoid having to have an extra variable.
     *
     * @param mixed      $value
     * @param mixed      $then
     * @param mixed|null $else
     *
     * @return mixed
     */
    function if_not_null($value, $then, $else = null)
    {
        return !is_null($value) ? $then : $else;
    }

    /**
     * @param string       $class
     * @param mixed|object $instance
     *
     * @return bool
     */
    function is(string $class, $instance): bool
    {
        return class_exists($class) && $instance instanceof $class;
    }

    function is_closure($instance): bool
    {
        return is(Closure::class, $instance);
    }

    /**
     * @param string       $class
     * @param mixed|object $instance
     *
     * @return bool
     */
    function is_not(string $class, $instance): bool
    {
        return is($class, $instance) === false;
    }

    /**
     * @param $instance
     *
     * @return bool
     */
    function is_not_closure($instance): bool
    {
        return is_closure($instance) === false;
    }

    /**
     * Reduce value if given a Closure otherwise return value as is.
     *
     * @param $value
     *
     * @return mixed
     */
    function reduce_value($value)
    {
        return is_closure($value) ? $value() : $value;
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
