<?php

use PhpWinTools\WmiScripting\ApiObjects\SWbemObject;
use PhpWinTools\WmiScripting\ApiObjects\SWbemLocator;
use PhpWinTools\WmiScripting\ApiObjects\SWbemObjectEx;
use PhpWinTools\WmiScripting\ApiObjects\SWbemProperty;
use PhpWinTools\WmiScripting\ApiObjects\SWbemServices;
use PhpWinTools\WmiScripting\ApiObjects\SWbemObjectSet;
use PhpWinTools\WmiScripting\ApiObjects\SWbemQualifier;
use PhpWinTools\WmiScripting\ApiObjects\SWbemObjectPath;
use PhpWinTools\WmiScripting\ApiObjects\SWbemPropertySet;
use PhpWinTools\WmiScripting\ApiObjects\SWbemQualifierSet;
use PhpWinTools\WmiScripting\Contracts\ApiObjects\Locator;
use PhpWinTools\WmiScripting\Contracts\ApiObjects\Property;
use PhpWinTools\WmiScripting\Contracts\ApiObjects\Services;
use PhpWinTools\WmiScripting\Contracts\ApiObjects\ObjectSet;
use PhpWinTools\WmiScripting\Contracts\ApiObjects\Qualifier;
use PhpWinTools\WmiScripting\Contracts\ApiObjects\ObjectItem;
use PhpWinTools\WmiScripting\Contracts\ApiObjects\ObjectPath;
use PhpWinTools\WmiScripting\Contracts\ApiObjects\PropertySet;
use PhpWinTools\WmiScripting\Contracts\ApiObjects\ObjectItemEx;
use PhpWinTools\WmiScripting\Contracts\ApiObjects\QualifierSet;

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
