<?php

namespace PhpWinTools\WmiScripting\Testing\Wmi;

use ReflectionClass;
use ReflectionProperty;
use ReflectionException;
use PhpWinTools\WmiScripting\Win32\Win32Model;
use PhpWinTools\WmiScripting\Collections\ArrayCollection;

class ModelProperties
{
    protected $class_name;

    protected $namespace;

    /** @var ArrayCollection */
    protected $properties;

    public function __construct($class_name)
    {
        $this->class_name = $class_name;

        $this->setProperties();
    }

    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @throws ReflectionException
     */
    protected function setProperties()
    {
        /** @var Win32Model $class */
        $reflectionClass = new ReflectionClass($class = new $this->class_name);

        $this->properties = (new ArrayCollection($reflectionClass->getProperties()))->mapWithKeys(
            function (ReflectionProperty $property) use ($class, $reflectionClass) {
                return [$property->name => [
                    'name' => $property->name,
                    'origin' => $this->getPropertyOrigin($property, $reflectionClass)->getName(),
                    'cast' => $class->getCast($property->name),
                    'doc' => $property->getDocComment(),
                    'value' => $class->getAttribute($property->name),
                ]];
            }
        )->diffKeys($class->getHidden())->filter(function ($property) {
            return $property['origin'] !== Win32Model::class;
        });
    }

    /**
     * Finds the origin of the model property. Could be made slightly more efficient, but haven't figured it out yet.
     *
     * @param ReflectionProperty $property
     * @param ReflectionClass $current_class
     * @param ReflectionClass|null $previous_class
     * @return ReflectionClass
     */
    protected function getPropertyOrigin(
        ReflectionProperty $property,
        ReflectionClass $current_class,
        ReflectionClass $previous_class = null
    ) {
        if ($property->getDeclaringClass()->getName() !== $current_class->getName() && !$previous_class) {
            return $property->getDeclaringClass();
        }

        if (!$current_class->hasProperty($property->getName())) {
            return $previous_class;
        }

        if ($class = $current_class->getParentClass()) {
            return $this->getPropertyOrigin($property, $class, $current_class);
        }

        return $current_class;
    }
}
