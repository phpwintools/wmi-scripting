<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects;

use Countable;
use ArrayAccess;
use PhpWinTools\WmiScripting\Win32Model;
use PhpWinTools\WmiScripting\Support\VariantWrapper;
use function PhpWinTools\WmiScripting\Support\resolve;
use PhpWinTools\WmiScripting\Support\ComVariantWrapper;
use PhpWinTools\WmiScripting\Collections\ModelCollection;
use PhpWinTools\WmiScripting\Collections\ObjectSetCollection;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\ObjectSet;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\ObjectItem;
use PhpWinTools\WmiScripting\Support\ApiObjects\VariantInterfaces\ObjectVariant;
use PhpWinTools\WmiScripting\Support\ApiObjects\VariantInterfaces\ObjectExVariant;
use PhpWinTools\WmiScripting\Support\ApiObjects\VariantInterfaces\ObjectSetVariant;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/wmisdk/swbemobjectset
 */
class SWbemObjectSet extends AbstractWbemObject implements ArrayAccess, Countable, ObjectSet
{
    protected $count = 0;

    /** @var ObjectSetCollection|array|SWbemObject[] */
    protected $set = [];

    protected $resolve_property_sets = [];

    /** @var VariantWrapper|ObjectSetVariant|ObjectVariant[]|ObjectExVariant[] */
    protected $object;

    public function __construct(VariantWrapper $variant, array $resolve_property_sets = [])
    {
        parent::__construct($variant);

        $this->count = $this->object->Count;
        $this->resolve_property_sets = $resolve_property_sets;
        $this->set = new ObjectSetCollection();
        $this->buildSet();
    }

    public function count()
    {
        return $this->count;
    }

    /**
     * @return Win32Model[]|ModelCollection
     */
    public function getSet(): ModelCollection
    {
        return new ModelCollection($this->set->map->getModel());
    }

    public function instantiateModels(Win32Model $model): ObjectSet
    {
        $this->set = $this->set->map(function (ObjectItem $item) use ($model) {
            $item->instantiateWin32Model($model);

            return $item;
        });

        return $this;
    }

    public function offsetExists($offset)
    {
        return $this->set->offsetExists($offset);
    }

    /**
     * @param mixed $offset
     *
     * @return SWbemObject
     */
    public function offsetGet($offset)
    {
        return $this->set->offsetGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->set->offsetSet($offset, $value);
    }

    public function offsetUnset($offset)
    {
        return $this->set->offsetUnset($offset);
    }

    protected function buildSet()
    {
        $is_object_ex = null;

        foreach ($this->object as $item) {
            $wbemObject = null;
            if (is_null($is_object_ex)) {
                $is_object_ex = $this->isWbemObjectEx($item);
            }

            if ($is_object_ex) {
                $wbemObject = resolve()->objectItemEx($item, $this->resolve_property_sets);
            }

            if (is_null($wbemObject)) {
                $wbemObject = resolve()->objectItem($item, $this->resolve_property_sets);
            }

            $this->set->add($wbemObject);
        }
    }

    protected function isWbemObjectEx(ComVariantWrapper $variant)
    {
        return $variant::getComClassName($variant) === 'ISWbemObjectEx';
    }
}
