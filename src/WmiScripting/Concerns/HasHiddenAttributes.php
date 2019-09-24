<?php

namespace PhpWinTools\WmiScripting\Concerns;

use function PhpWinTools\WmiScripting\Support\get_ancestor_property;

trait HasHiddenAttributes
{
    protected $hidden_booted = false;

    protected $trait_hidden_attributes = [
        'trait_hidden_attributes',
        'trait_name_replacements',

        'attribute_name_replacements',
        'unmapped_attributes',

        'hidden_booted',
        'hidden_attributes',
        'merge_parent_hidden_attributes',

        'casts_booted',
        'attribute_casting',
        'merge_parent_casting',
    ];

    public function mergeHiddenAttributes(array $hidden_attributes, bool $merge_hidden = true)
    {
        $hidden_attributes = $merge_hidden
            ? array_merge(get_ancestor_property(get_called_class(), 'hidden_attributes'), $hidden_attributes)
            : $hidden_attributes;

        $this->trait_hidden_attributes = array_merge($this->trait_hidden_attributes, $hidden_attributes);

        $this->bootHiddenAttributes();

        return $this;
    }

    public function getHiddenAttributes()
    {
        if (!$this->hidden_booted) {
            $this->bootHiddenAttributes();
        }

        return $this->trait_hidden_attributes;
    }

    public function isHidden($key): bool
    {
        return array_key_exists($key, $this->getHiddenAttributes());
    }

    protected function bootHiddenAttributes()
    {
        $this->trait_hidden_attributes = array_combine($this->trait_hidden_attributes, $this->trait_hidden_attributes);

        $this->hidden_booted = true;
    }
}
