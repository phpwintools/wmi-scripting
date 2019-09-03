<?php

namespace PhpWinTools\WmiScripting\Models;

use PhpWinTools\WmiScripting\Connection;
use PhpWinTools\WmiScripting\Query\Builder;
use PhpWinTools\WmiScripting\Contracts\Jsonable;
use PhpWinTools\WmiScripting\Contracts\Arrayable;
use PhpWinTools\WmiScripting\Contracts\HasAttributes;
use PhpWinTools\WmiScripting\MappingStrings\Mappings;
use PhpWinTools\WmiScripting\Contracts\CastsAttributes;
use PhpWinTools\WmiScripting\Contracts\HidesAttributes;
use function PhpWinTools\WmiScripting\Support\connection;
use PhpWinTools\WmiScripting\Collections\ModelCollection;
use PhpWinTools\WmiScripting\Concerns\HasHiddenAttributes;
use PhpWinTools\WmiScripting\Concerns\HasCastableAttributes;
use PhpWinTools\WmiScripting\Concerns\HasArrayableAttributes;
use function PhpWinTools\WmiScripting\Support\class_has_property;
use PhpWinTools\WmiScripting\Exceptions\InvalidArgumentException;
use PhpWinTools\WmiScripting\Exceptions\WmiClassNotFoundException;
use PhpWinTools\WmiScripting\Exceptions\InvalidConnectionException;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\ObjectPath;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/cimwin32prov/win32-provider
 */
class Win32Model implements Arrayable, Jsonable, HasAttributes, HidesAttributes, CastsAttributes
{
    use HasArrayableAttributes,
        HasHiddenAttributes,
        HasCastableAttributes;

    /** @var string */
    protected $uuid;

    /** @var ObjectPath|null */
    protected $objectPath;

    /** @var Builder|null */
    protected $queryBuilder;

    /** @var array */
    protected $hidden_attributes = ['queryBuilder', 'connection', 'wmi_class_name'];

    /** @var bool */
    protected $merge_parent_casting = true;

    /** @var bool */
    protected $merge_parent_hidden_attributes = true;

    /** @var array */
    protected $attribute_casting = [];

    protected $connection = 'default';

    protected $wmi_class_name;

    /* TODO: Remove ObjectPath dependency. Should be passed into attributes */
    public function __construct(array $attributes = [], ObjectPath $objectPath = null)
    {
        $this->mergeHiddenAttributes($this->hidden_attributes, $this->merge_parent_hidden_attributes);
        $this->fill($attributes);

        $this->objectPath = $objectPath;
    }

    /**
     * @param array           $attributes
     * @param ObjectPath|null $objectPath
     *
     * @return Win32Model
     */
    public static function newInstance(array $attributes = [], ObjectPath $objectPath = null)
    {
        return new static($attributes, $objectPath);
    }

    /**
     * @param Connection|string|null $connection
     *
     * @return ModelCollection|Win32Model[]
     */
    public static function all($connection = null)
    {
        return static::query(static::newInstance()->getConnection($connection))->all();
    }

    /**
     * @param Connection|string|null $connection
     *
     * @return Builder
     */
    public static function query($connection = null)
    {
        return new Builder($self = static::newInstance(), $self->getConnection($connection));
    }

    /**
     * @return string
     */
    public function getConnectionName()
    {
        return $this->connection;
    }

    /**
     * @param Connection|string|null $connection
     *
     * @throws InvalidConnectionException
     *
     * @return Connection
     */
    public function getConnection($connection = null)
    {
        return connection($connection, $this->connection);
    }

    /**
     * @return string
     */
    public function getModelNameAttribute()
    {
        return $this->getClassName();
    }

    /**
     * @return string
     */
    public function getWmiClassNameAttribute()
    {
        if (!is_null($this->wmi_class_name)) {
            return $this->wmi_class_name;
        }

        if ($this->wmi_class_name = $this->wmiClassNameSearch()) {
            return $this->wmi_class_name;
        }

        throw new WmiClassNotFoundException(
            'Cannot find a suitable WMI Class to query. Please set $wmi_class_name manually.'
        );
    }

    /**
     * Uses the Classes constant class to map models to WMI models for querying. If the current instance
     * does not yield results then we search the class' parents until a suitable match is found.
     *
     * @return string|null
     */
    protected function wmiClassNameSearch()
    {
        if ($result = array_search(static::class, Classes::CLASS_MAP, true)) {
            return $result;
        }

        foreach (class_parents($this) as $parent) {
            if ($result = array_search($parent, Classes::CLASS_MAP, true)) {
                return $result;
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        $classname = get_called_class();

        if (preg_match('@\\\\([\w]+)$@', $classname, $matches)) {
            $classname = $matches[1];
        }

        return $classname;
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->toJson();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param array $attributes
     */
    protected function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $key = lcfirst($key);
            $value = $this->reduceValueArray($value);
            if (class_has_property(get_called_class(), $key)) {
                $this->{$key} = $this->cast($key, $value);
                continue;
            }

            $this->unmapped_attributes[$key] = $this->cast($key, $value);
        }
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    protected function reduceValueArray($value)
    {
        if (is_array($value) && array_key_exists('value', $value)) {
            $value = $value['value'];
        }

        return $value;
    }

    /**
     * @param Mappings|string $mapping_string_class
     * @param mixed           $constant
     *
     * @throws InvalidArgumentException
     *
     * @return mixed
     */
    protected function mapConstant(string $mapping_string_class, $constant)
    {
        if (!array_key_exists(Mappings::class, class_parents($mapping_string_class))) {
            throw new InvalidArgumentException("{$mapping_string_class} must extend " . Mappings::class);
        }

        if (trim($type = call_user_func_array($mapping_string_class . '::string', [$constant])) === '') {
            return "[{$constant}] - UNKNOWN";
        }

        return $type;
    }
}
