<?php

namespace PhpWinTools\WmiScripting\Support\ApiObjects;

use PhpWinTools\Support\COM\VariantWrapper;
use PhpWinTools\WmiScripting\Models\Classes;
use PhpWinTools\WmiScripting\Models\Win32Model;
use function PhpWinTools\WmiScripting\Support\resolve;
use PhpWinTools\WmiScripting\Support\ApiObjects\Contracts\ObjectItem;
use PhpWinTools\WmiScripting\Support\ApiObjects\VariantInterfaces\ObjectVariant;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/wmisdk/swbemobject
 */
class SWbemObject extends AbstractWbemObject implements ObjectItem
{
    protected $win32Model;

    protected $derivations = [];

    protected $propertySet;

    protected $qualifierSet;

    protected $mergedProperties = [];

    protected $path;

    /** @var VariantWrapper|ObjectVariant */
    protected $object;

    public function __construct(VariantWrapper $variant, array $resolve_property_sets = [])
    {
        parent::__construct($variant);

        $this->buildDerivations();

        $this->propertySet = resolve()->propertySet($this->object->Properties_, $resolve_property_sets);
        $this->qualifierSet = resolve()->qualifierSet($this->object->Qualifiers_);
        $this->path = resolve()->objectPath($this->object->Path_);

        $this->mergedProperties = array_merge(
            $this->propertySet->toArray(),
            ['qualifiers' => $this->qualifierSet->toArray()['qualifiers']],
            ['path' => $this->path->toArray()],
            ['derivations' => $this->derivations]
        );

        $this->instantiateWin32Model();
    }

    /**
     * @return Win32Model
     */
    public function getModel(): Win32Model
    {
        return $this->getAttribute('win32Model');
    }

    public function instantiateWin32Model(Win32Model $model = null): Win32Model
    {
        $model = $model ?? Classes::getClass($this->path->getAttribute('class'));

        if (!is_null($this->win32Model) && get_class($this->win32Model) === $model) {
            return $this->win32Model;
        }

        return $this->win32Model = new $model($this->mergedProperties);
//        return $this->win32Model = new $model($this->propertySet->toArray(), $this->path);
    }

    protected function buildDerivations()
    {
        $this->derivations = $this->object->propertyToArray('Derivation_');
    }
}
