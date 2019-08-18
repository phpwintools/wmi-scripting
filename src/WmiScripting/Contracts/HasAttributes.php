<?php

namespace PhpWinTools\WmiScripting\Contracts;

interface HasAttributes extends Arrayable
{
    public function getAttribute($attribute, $default = null);

    public function mergeHiddenAttributes(array $hidden_attributes, bool $merge_hidden = true);

    public function getHiddenAttributes();

    public function isHidden($attribute): bool;

    public function getCasts(): array;

    public function getCast($attribute);

    public function setCasts(array $attribute_casting, bool $merge_casting = true);
}
