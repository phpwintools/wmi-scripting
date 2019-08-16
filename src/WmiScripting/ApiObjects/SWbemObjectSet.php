<?php

namespace PhpWinTools\WmiScripting\ApiObjects;

use Countable;
use ArrayAccess;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Win32\Win32Model;
use PhpWinTools\WmiScripting\Support\VariantWrapper;
use PhpWinTools\WmiScripting\Support\ComVariantWrapper;
use PhpWinTools\WmiScripting\Collections\ModelCollection;
use PhpWinTools\WmiScripting\Contracts\ApiObjects\ObjectSet;
use PhpWinTools\WmiScripting\Collections\ObjectSetCollection;
use PhpWinTools\WmiScripting\Contracts\ApiObjects\ObjectItem;

/**
 * Class SWbemObjectSet
 * @package App\Transformers\Com\Wmi
 * https://docs.microsoft.com/en-us/windows/win32/wmisdk/swbemobjectset
 */
class SWbemObjectSet extends AbstractWbemObject implements ArrayAccess, Countable, ObjectSet
{
    protected $count = 0;

    /** @var ObjectSetCollection|array|SWbemObject[] */
    protected $set = [];

    protected $resolve_property_sets = [];

    public function __construct(VariantWrapper $variant, array $resolve_property_sets = [], Config $config = null)
    {
        parent::__construct($variant, $config);

        $this->count = $variant->Count;
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
                $wbemObject = $this->make()->objectItemEx($item, $this->resolve_property_sets, $this->config);
            }

            if (is_null($wbemObject)) {
                $wbemObject = $this->make()->objectItem($item, $this->resolve_property_sets, $this->config);
            }

            $this->set->add($wbemObject);
        }
    }

    protected function isWbemObjectEx(ComVariantWrapper $variant)
    {
        return $variant::getComClassName($variant) === "ISWbemObjectEx";
    }
}