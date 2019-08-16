<?php

namespace PhpWinTools\WmiScripting\Testing\Com\Wmi;

use Faker\Factory;
use Faker\Generator;
use PhpWinTools\WmiScripting\Connection;
use PhpWinTools\WmiScripting\Win32\Win32Model;
use PhpWinTools\WmiScripting\Collections\ArrayCollection;
use PhpWinTools\WmiScripting\ApiObjects\SWbemServices;

class Win32ModelFactory
{
    protected $faker;

    public function __construct(Generator $faker = null)
    {
        $this->faker = $faker ?? Factory::create();
    }

    /**
     * @param string|Win32Model $class_name
     * @param int $count
     * @param array $attributes
     * @param Generator|null $faker
     * @return ModelFakeCollection
     */
    public static function make($class_name, int $count = 1, array $attributes = [], Generator $faker = null)
    {
        $factory = new static($faker ?? Factory::create());

        $modelFakes = new ModelFakeCollection();

        while ($count) {
            $modelFakes->add(new ModelFake([
                'properties' => $factory->fillAttributes(new ModelProperties($class_name)),
                'derivations' => array_filter(class_parents($class_name), function ($parent) {
                    return $parent !== Win32Model::class;
                }),
                'qualifiers' => new ArrayCollection([
                    [
                        'Name' => 'provider',
                        'Value' => 'CIMWin32',
                    ],
                    [
                        'Name' => 'UUID',
                        'Value' => $class_name::newInstance()->getAttribute('uuid'),
                    ],
                ]),
                'path' => new ArrayCollection([
                    'authority' => $authority = null,
                    'class' => $class_name::newInstance()->getWmiClassNameAttribute(),
                    'display_name' => SWbemServices::WMI_MONIKER
                        . '{authenticationLevel=pktPrivacy,impersonationLevel=impersonate}!'
                        . "\\\server\\namespace:{$class_name::newInstance()->getWmiClassNameAttribute()}"
                        . ".DeviceID=\"something:\"",
                    'is_class' => false,
                    'is_singleton' => false,
                    'keys' => [],
                    'namespace' => Connection::DEFAULT_NAMESPACE,
                    'parent_namespace' => "Root",
                    'path' => "\\\server\\namespace:{$class_name::newInstance()->getWmiClassNameAttribute()}"
                        . ".DeviceID=\"something:\"",
                    'relative_path' => "some other stuff",
                    'server' => 'server',
                ]),
            ]));

            --$count;
        }

        return $modelFakes;
    }

    protected function fillAttributes(ModelProperties $details)
    {
        return $details->getProperties()->map(function ($property) {
            $value = null;

            if (is_array($property['value']) && empty($property['value'])) {
                $value = (array) $this->faker->words;
            }

            if (is_array($property['value']) && !empty($property['value'])) {
                $value = $property['value'];
            }

            if (is_string($property['value']) && trim($property['value']) !== '') {
                $value = $property['value'];
            }

            if (is_null($value)) {
                $value = $this->cast($property['cast']);
            }

            return [
                'Name' => $property['name'],
                'Value' => $value,
                'Origin' => $property['origin'],
            ];
        });
    }

    protected function cast($cast)
    {
        switch ($cast) {
            case 'array':
                return (array) $this->faker->words;
            case 'bool':
            case 'boolean':
                return (bool) $this->faker->boolean;
            case 'float':
                return (float) $this->faker->randomFloat(2, PHP_INT_MIN, PHP_INT_MAX);
            case 'int':
            case 'integer':
                // Prevent integer overflow
                return (int) $this->faker->numberBetween(0, PHP_INT_MAX);
            case 'string':
            default:
                return $this->faker->word;
        }
    }
}
