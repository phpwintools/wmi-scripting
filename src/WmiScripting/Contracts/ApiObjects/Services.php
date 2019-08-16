<?php

namespace PhpWinTools\WmiScripting\Contracts\ApiObjects;

use PhpWinTools\WmiScripting\Flags\WbemFlags;

interface Services extends WbemObject
{
    public function instancesOf(
        string $class,
        $flags = WbemFlags::RETURN_IMMEDIATELY,
        $wbemNamedValueSet = null
    ): ObjectSet;

    public function execQuery(
        string $query,
        $query_language = null,
        $flags = WbemFlags::RETURN_IMMEDIATELY,
        $wbemNamedValueSet = null
    ): ObjectSet;

    public function get(string $object_path, $flags = null, $wbemNamedValueSet = null): ObjectItem;

    public function resolvePropertySets(array $property_set_names): Services;
}
