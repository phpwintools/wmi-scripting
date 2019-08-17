<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects;

use PhpWinTools\WmiScripting\Connection;
use PhpWinTools\WmiScripting\Flags\WbemFlags;
use function PhpWinTools\WmiScripting\Support\resolve;
use PhpWinTools\WmiScripting\Support\ComVariantWrapper;
use function PhpWinTools\WmiScripting\Support\connection;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\Services;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\ObjectSet;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\ObjectItem;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/wmisdk/swbemservices
 */
class SWbemServices extends AbstractWbemObject implements Services
{
    const WMI_MONIKER = 'WINMGMTS:';

    protected $resolve_property_sets = [];

    public function __construct(ComVariantWrapper $object = null, Connection $connection = null)
    {
        $connection = connection($connection);
        $object = $object ?? resolve()->comWrapper(
            resolve()->comClass(static::WMI_MONIKER . "\\\\{$connection->getServer()}\\{$connection->getNamespace()}")
        );
        parent::__construct($object);

        $this->resolve_property_sets['services'] = $this;
        $this->resolve_property_sets['property_names'] = [];
    }

    /**
     * https://docs.microsoft.com/en-us/windows/win32/wmisdk/swbemservices-instancesof
     *
     * @param string $class
     * @param int $flags
     * @param null $wbemNamedValueSet
     * @return SWbemObjectSet
     */
    public function instancesOf(
        string $class,
        $flags = WbemFlags::RETURN_IMMEDIATELY,
        $wbemNamedValueSet = null
    ): ObjectSet {
        return resolve()->objectSet(
            $this->object->InstancesOf($class, $flags, $wbemNamedValueSet),
            $this->resolve_property_sets
        );
    }

    /**
     * https://docs.microsoft.com/en-us/windows/win32/wmisdk/wql-sql-for-wmi
     * https://docs.microsoft.com/en-us/windows/win32/wmisdk/swbemservices-execquery
     *
     * @param string $query
     * @param null $query_language
     * @param int $flags
     * @param null $wbemNamedValueSet
     * @return ObjectSet
     */
    public function execQuery(
        string $query,
        $query_language = null,
        $flags = WbemFlags::RETURN_IMMEDIATELY,
        $wbemNamedValueSet = null
    ): ObjectSet {
        return resolve()->objectSet(
            $this->object->ExecQuery($query, $query_language, $flags, $wbemNamedValueSet),
            $this->resolve_property_sets
        );
    }

    public function get(string $object_path, $flags = null, $wbemNamedValueSet = null): ObjectItem
    {
        return resolve()->objectItem($this->object->Get($object_path, $flags, $wbemNamedValueSet));
    }

    public function resolvePropertySets(array $property_set_names): Services
    {
        $this->resolve_property_sets['property_names'] = $property_set_names;

        return $this;
    }
}
