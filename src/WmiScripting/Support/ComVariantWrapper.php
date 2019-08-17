<?php

namespace PhpWinTools\WmiScripting\Support;

use ArrayIterator;
use COM as ComExt;
use IteratorAggregate;
use VARIANT as VariantExt;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Testing\Support\COMObjectFake;
use PhpWinTools\WmiScripting\Testing\Support\ComVariantWrapperFake;
use PhpWinTools\WmiScripting\Support\ApiObjects\VariantInterfaces\AllVariantsInterface;

class ComVariantWrapper implements IteratorAggregate
{
    /** @var ComExt|VariantExt */
    protected $comObject;

    protected $config;

    public function __construct($comObject, Config $config = null)
    {
        $this->comObject = $comObject;
        $this->config = $config ?? Config::instance();
    }

    public static function comToString(self $com)
    {
        ob_start();
        com_print_typeinfo($com->getComObject());
        $com_string = ob_get_contents();
        ob_end_clean();

        return $com_string;
    }

    public static function getComClassName(self $com)
    {
        if ($com->getComObject() instanceof COMObjectFake) {
            return ComVariantWrapperFake::comToString($com);
        }

        preg_match('~(?<=\bclass\s)(\w+)~', self::comToString($com), $matches, 0, 0);

        return $matches[0];
    }

    public function getComObject()
    {
        return $this->comObject;
    }

    public function canIterateObject()
    {
        try {
            foreach ($this->comObject as $item) {
                return true;
            }
        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    public function propertyExists(string $property)
    {
        try {
            $this->comObject->{$property};

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function canIterateProperty(string $property)
    {
        if (!$this->propertyExists($property)) {
            return false;
        }

        try {
            foreach ($this->comObject->{$property} as $item) {
                return true;
            }
        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    public function propertyToArray(string $property)
    {
        if (!$this->canIterateProperty($property)) {
            return [];
        }

        $items = [];

        foreach ($this->comObject->{$property} as $item) {
            $items[] = $this->transformResult($item);
        }

        return $items;
    }

    /**
     * @param $property
     * @return mixed|AllVariantsInterface|ComWrapper|VariantWrapper
     */
    public function __get($property)
    {
        $result = $this->comObject->{$property};
        // this could fail so add exception

        return $this->transformResult($result);
    }

    /**
     * @param $method
     * @param $arguments
     *
     * @return mixed|AllVariantsInterface|ComWrapper|VariantWrapper
     */
    public function __call($method, $arguments)
    {
        $result = $this->comObject->{$method}(...$arguments);
        // this could fail so add exception

        return $this->transformResult($result);
    }

    protected function resolve(string $class = null, ...$parameters)
    {
        return ($this->config)($class, $parameters);
    }

    /**
     * @param $result
     *
     * @return mixed|AllVariantsInterface|ComWrapper|VariantWrapper
     */
    protected function transformResult($result)
    {
        if ($result instanceof VariantExt) {
            $result = $this->resolve()->variantWrapper($result, $this->config);
        }

        if ($result instanceof ComExt) {
            $result = $this->resolve()->comWrapper($result, $this->config);
        }

        return $result;
    }

    public function getIterator()
    {
        $items = [];

        foreach ($this->comObject as $item) {
            $items[] = $this->transformResult($item);
        }

        return new ArrayIterator($items);
    }
}
