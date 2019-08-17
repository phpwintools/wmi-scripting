<?php

namespace PhpWinTools\WmiScripting\Configuration;

use COM;
use PhpWinTools\WmiScripting\Testing\Support\COMFake;
use PhpWinTools\WmiScripting\Testing\Support\VARIANTFake;
use VARIANT;
use PhpWinTools\WmiScripting\Connection;
use PhpWinTools\WmiScripting\Support\ComWrapper;
use PhpWinTools\WmiScripting\Support\VariantWrapper;
use PhpWinTools\WmiScripting\Support\ComVariantWrapper;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\Locator;
use PhpWinTools\WmiScripting\Exceptions\UnresolvableClassException;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\Property;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\Services;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\ObjectSet;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\Qualifier;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\ObjectItem;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\ObjectPath;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\PropertySet;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\ObjectItemEx;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\QualifierSet;

class Resolver
{
    /** @var Config */
    protected $config;

    public function __construct(Config $config = null)
    {
        $this->config = $config ?? Config::instance();
    }

    /**
     * @param string|callable|object $resolvable
     * @param mixed                  ...$parameters
     *
     * @return mixed
     */
    public function make($resolvable, ...$parameters)
    {
        if (is_callable($resolvable)) {
            return $resolvable(...$parameters);
        }

        if (is_object($resolvable)) {
            return $resolvable;
        }

        if (is_string($resolvable) && class_exists($resolvable)) {
            return new $resolvable(...$parameters);
        }

        throw UnresolvableClassException::default($resolvable);
    }

    public function comClass($module_name, $server_name = null, $code_page = null, $type_lib = null)
    {
        return $this->make(
            $this->config->getComClass(),
            $module_name,
            $server_name = null,
            $code_page = null,
            $type_lib = null
        );
    }

    public function variantClass($value = null, string $class_name = null, $code_page = null)
    {
        return $this->make($this->config->getVariantClass(), $value = null, $class_name = null, $code_page = null);
    }

    /**
     * @param COM|COMFake $com
     * @param Config|null $config
     *
     * @return ComVariantWrapper
     */
    public function comVariantWrapper($com, Config $config = null)
    {
        return $this->make($this->config->getComVariantWrapper(), $com, $config ?? $config);
    }

    /**
     * @param COM|COMFake $com
     * @param Config|null $config
     *
     * @return ComWrapper
     */
    public function comWrapper($com, Config $config = null)
    {
        return $this->make($this->config->getComWrapper(), $com, $config ?? $this->config);
    }

    /**
     * @param VARIANT|VARIANTFake $variant
     * @param Config|null         $config
     *
     * @return VariantWrapper
     */
    public function variantWrapper($variant, Config $config = null)
    {
        return $this->make($this->config->getVariantWrapper(), $variant, $config ?? $this->config);
    }

    /**
     * @param ComVariantWrapper|null $wrapper
     * @param Config|null            $config
     *
     * @return Locator
     */
    public function locator(ComVariantWrapper $wrapper = null, Config $config = null)
    {
        return $this->make($this->config->getApiObject(Locator::class), $wrapper, $config ?? $this->config);
    }

    public function objectItem(VariantWrapper $variant, array $resolve = [], Config $config = null)
    {
        return $this->make(
            $this->config->getApiObject(ObjectItem::class),
            $variant,
            $resolve,
            $config ?? $this->config
        );
    }

    /**
     * @param VariantWrapper $variant
     * @param array          $resolve
     * @param Config|null    $config
     *
     * @return ObjectItemEx
     */
    public function objectItemEx(VariantWrapper $variant, array $resolve = [], Config $config = null)
    {
        return $this->make(
            $this->config->getApiObject(ObjectItemEx::class),
            $variant,
            $resolve,
            $config ?? $this->config
        );
    }

    /**
     * @param VariantWrapper $variant
     * @param Config|null    $config
     *
     * @return ObjectPath
     */
    public function objectPath(VariantWrapper $variant, Config $config = null)
    {
        return $this->make($this->config->getApiObject(ObjectPath::class), $variant, $config ?? $this->config);
    }

    /**
     * @param VariantWrapper $variant
     * @param array          $resolve
     * @param Config|null    $config
     *
     * @return ObjectSet
     */
    public function objectSet(VariantWrapper $variant, array $resolve = [], Config $config = null)
    {
        return $this->make($this->config->getApiObject(ObjectSet::class), $variant, $resolve, $config ?? $this->config);
    }

    /**
     * @param VariantWrapper $variant
     * @param Config|null    $config
     *
     * @return Property
     */
    public function property(VariantWrapper $variant, Config $config = null)
    {
        return $this->make($this->config->getApiObject(Property::class), $variant, $config ?? $this->config);
    }

    /**
     * @param VariantWrapper $variant
     * @param array          $resolve
     * @param Config|null    $config
     *
     * @return PropertySet
     */
    public function propertySet(VariantWrapper $variant, array $resolve = [], Config $config = null)
    {
        return $this->make(
            $this->config->getApiObject(PropertySet::class),
            $variant,
            $resolve,
            $config ?? $this->config
        );
    }

    /**
     * @param VariantWrapper $variant
     * @param Config|null    $config
     *
     * @return Qualifier
     */
    public function qualifier(VariantWrapper $variant, Config $config = null)
    {
        return $this->make($this->config->getApiObject(Qualifier::class), $variant, $config ?? $this->config);
    }

    /**
     * @param VariantWrapper $variant
     * @param Config|null    $config
     *
     * @return QualifierSet
     */
    public function qualifierSet(VariantWrapper $variant, Config $config = null)
    {
        return $this->make($this->config->getApiObject(QualifierSet::class), $variant, $config ?? $this->config);
    }

    /**
     * @param ComVariantWrapper|null $com
     * @param Connection|null        $connection
     * @param Config|null            $config
     *
     * @return Services
     */
    public function services(ComVariantWrapper $com = null, Connection $connection = null, Config $config = null)
    {
        return $this->make($this->config->getApiObject(Services::class), $com, $connection, $config ?? $this->config);
    }
}
