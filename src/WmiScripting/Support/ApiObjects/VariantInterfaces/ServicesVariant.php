<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects\VariantInterfaces;

use PhpWinTools\WmiScripting\Support\VariantWrapper;

interface ServicesVariant extends VariantInterface
{
    /**
     * @param $class
     * @param $flags
     * @param $wbemNamedValueSet
     *
     * @return VariantWrapper|ObjectSetVariant
     */
    public function InstancesOf($class, $flags, $wbemNamedValueSet);

    /**
     * @param $query
     * @param $query_language
     * @param $flags
     * @param $wbemNamedValueSet
     *
     * @return VariantWrapper|ObjectSetVariant
     */
    public function ExecQuery($query, $query_language, $flags, $wbemNamedValueSet);

    /**
     * @param $object_path
     * @param $flags
     * @param $wbemNamedValueSet
     *
     * @return VariantWrapper|ObjectVariant
     */
    public function Get($object_path, $flags, $wbemNamedValueSet);
}
