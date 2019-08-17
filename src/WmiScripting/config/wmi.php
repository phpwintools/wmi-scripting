<?php

use PhpWinTools\WmiScripting\Support\ApiObjects\SWbemObject;
use PhpWinTools\WmiScripting\Support\ApiObjects\SWbemLocator;
use PhpWinTools\WmiScripting\Support\ApiObjects\SWbemObjectEx;
use PhpWinTools\WmiScripting\Support\ApiObjects\SWbemProperty;
use PhpWinTools\WmiScripting\Support\ApiObjects\SWbemServices;
use PhpWinTools\WmiScripting\Support\ApiObjects\SWbemObjectSet;
use PhpWinTools\WmiScripting\Support\ApiObjects\SWbemQualifier;
use PhpWinTools\WmiScripting\Support\ApiObjects\SWbemObjectPath;
use PhpWinTools\WmiScripting\Support\ApiObjects\SWbemPropertySet;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\Locator;
use PhpWinTools\WmiScripting\Support\ApiObjects\SWbemQualifierSet;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\Property;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\Services;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\ObjectSet;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\Qualifier;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\ObjectItem;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\ObjectPath;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\PropertySet;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\ObjectItemEx;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\QualifierSet;

return [
    'api_objects' => [
        Locator::class      => SWbemLocator::class,
        ObjectItem::class   => SWbemObject::class,
        ObjectItemEx::class => SWbemObjectEx::class,
        ObjectPath::class   => SWbemObjectPath::class,
        ObjectSet::class    => SWbemObjectSet::class,
        Property::class     => SWbemProperty::class,
        PropertySet::class  => SWbemPropertySet::class,
        Qualifier::class    => SWbemQualifier::class,
        QualifierSet::class => SWbemQualifierSet::class,
        Services::class     => SWbemServices::class,
    ],

    'connections' => include('connections.php'),
];
