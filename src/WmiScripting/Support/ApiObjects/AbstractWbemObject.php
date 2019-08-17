<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects;

use PhpWinTools\WmiScripting\Contracts\Jsonable;
use PhpWinTools\WmiScripting\Contracts\Arrayable;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Concerns\ComHasAttributes;
use PhpWinTools\WmiScripting\Support\ComVariantWrapper;

use function PhpWinTools\WmiScripting\Support\resolve;

/**
 * Class AbstractWbemObject
 * @package App\Transformers\Com\Wmi
 * https://docs.microsoft.com/en-us/windows/win32/wmisdk/winmgmt
 */
class AbstractWbemObject implements Arrayable, Jsonable
{
    use ComHasAttributes;

    protected $object;

    protected $merge_parent_casting = true;

    protected $merge_parent_hidden_attributes = true;

    protected $hidden_attributes = ['object', 'services', 'resolve_property_sets', 'config'];

    protected $attribute_name_replacements = [];

    protected $config;

    public function __construct(ComVariantWrapper $object, Config $config = null)
    {
        $this->object = $object;
        $this->config = $config ?? Config::instance();
        $this->setHidden($this->hidden_attributes, $this->merge_parent_hidden_attributes);
        $this->setAttributeNameReplacements($this->attribute_name_replacements);
    }

    public function make(string $class = null, ... $parameters)
    {
        return resolve($class, $parameters);
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function toString(): string
    {
        return $this->toJson();
    }

    public function __toString()
    {
        return $this->toString();
    }
}
