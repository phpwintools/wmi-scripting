<?php

namespace PhpWinTools\WmiScripting\Testing\Com\Responses;

use ArrayIterator;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Support\VariantWrapper;
use PhpWinTools\WmiScripting\Testing\Com\Wmi\ModelFake;
use PhpWinTools\WmiScripting\Testing\Com\Support\VARIANTFake;
use PhpWinTools\WmiScripting\Support\ApiObjects\SWbemObjectSet;
use PhpWinTools\WmiScripting\Testing\Com\Wmi\ModelFakeCollection;

class ObjectSetResponse extends ApiObjectResponseFactory
{
    public static function standard(ModelFakeCollection $collection, Config $config = null)
    {
        $config = $config ?? Config::testInstance();

        static::addVariantResponse(SWbemObjectSet::class, 'Count', $collection->count(), $config);
        static::addVariantResponse(SWbemObjectSet::class, '__objectSet__', static::buildObjects($collection), $config);
    }

    protected static function buildObjects(ModelFakeCollection $collection)
    {
        return new ArrayIterator($collection->map(function (ModelFake $modelFake) {
            return new VariantWrapper(
                VARIANTFake::withResponse('Properties_', static::buildProperties($modelFake))
                    ->addResponse('Derivation_', $modelFake->get('derivations'))
                    ->addResponse('Qualifiers_', static::buildQualifiers($modelFake))
                    ->addResponse('Path_', static::buildPath($modelFake))
            );
        })->toArray());
    }

    protected static function buildPath(ModelFake $modelFake)
    {
        $path = $modelFake->get('path');

        return new VariantWrapper(
            VARIANTFake::withResponse('Authority', $path['authority'])
                ->addResponse('Class', $path['class'])
                ->addResponse('DisplayName', $path['display_name'])
                ->addResponse('IsClass', $path['is_class'])
                ->addResponse('IsSingleton', $path['is_singleton'])
                ->addResponse('Keys', $path['keys'])
                ->addResponse('Namespace', $path['namespace'])
                ->addResponse('ParentNamespace', $path['parent_namespace'])
                ->addResponse('Path', $path['path'])
                ->addResponse('RelPath', $path['relative_path'])
                ->addResponse('Server', $path['server'])
        );
    }

    protected static function buildProperties(ModelFake $modelFake)
    {
        $properties = $modelFake->get('properties')->map(function ($property) {
            return new VariantWrapper(
                VARIANTFake::withResponse('Name', $property['Name'])
                    ->addResponse('Value', $property['Value'])
                    ->addResponse('Origin', $property['Origin'])
            );
        })->flatten()->toArray();

        return new VariantWrapper(VARIANTFake::withResponse('__propertySet__', new ArrayIterator($properties)));
    }

    protected static function buildQualifiers(ModelFake $modelFake)
    {
        $qualifiers = $modelFake->get('qualifiers')->map(function ($qualifier) {
            return new VariantWrapper(
                VARIANTFake::withResponse('Name', $qualifier['Name'])
                    ->addResponse('Value', $qualifier['Value'])
            );
        })->flatten()->toArray();

        return new VariantWrapper(
            VARIANTFake::withResponse('__qualifierSet__', new ArrayIterator($qualifiers))
                ->addResponse('Count', $modelFake->get('qualifiers')->count())
        );
    }
}
