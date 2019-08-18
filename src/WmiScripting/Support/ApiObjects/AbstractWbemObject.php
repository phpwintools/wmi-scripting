<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects;

use PhpWinTools\Support\COM\ComVariantWrapper;
use PhpWinTools\WmiScripting\Contracts\Jsonable;
use PhpWinTools\WmiScripting\Contracts\Arrayable;
use PhpWinTools\WmiScripting\Concerns\ComHasAttributes;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/wmisdk/winmgmt
 */
class AbstractWbemObject implements Arrayable, Jsonable
{
    use ComHasAttributes;

    protected $object;

    protected $merge_parent_casting = true;

    protected $merge_parent_hidden_attributes = true;

    protected $hidden_attributes = ['object', 'services', 'resolve_property_sets', 'config'];

    public function __construct(ComVariantWrapper $object)
    {
        $this->object = $object;
        $this->mergeHiddenAttributes($this->hidden_attributes, $this->merge_parent_hidden_attributes);
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
