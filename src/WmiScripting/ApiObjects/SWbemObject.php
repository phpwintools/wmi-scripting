<?php

namespace PhpWinTools\WmiScripting\ApiObjects;

use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Win32\Win32Model;
use PhpWinTools\WmiScripting\Support\VariantWrapper;
use PhpWinTools\WmiScripting\Win32\Models\Classes;
use PhpWinTools\WmiScripting\Contracts\ApiObjects\ObjectItem;

/**
 * Class SWbemObject
 * @package App\Transformers\Com\Wmi
 * https://docs.microsoft.com/en-us/windows/win32/wmisdk/swbemobject
 */
class SWbemObject extends AbstractWbemObject implements ObjectItem
{
    protected $win32Model;

    protected $derivations = [];

    protected $propertySet;

    protected $qualifierSet;

    protected $path;

    public function __construct(VariantWrapper $variant, array $resolve_property_sets = [], Config $config = null)
    {
        parent::__construct($variant, $config);

        $this->buildDerivations();

        $this->propertySet = $this->make()->propertySet(
            $this->object->Properties_,
            $resolve_property_sets,
            $this->config
        );
        $this->qualifierSet = $this->make()->qualifierSet($this->object->Qualifiers_, $this->config);
        $this->path = $this->make()->objectPath($this->object->Path_, $this->config);

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

        return $this->win32Model = new $model($this->propertySet->toArray(), $this->path);
    }

    protected function buildDerivations()
    {
        $this->derivations = $this->object->propertyToArray('Derivation_');
    }
}
