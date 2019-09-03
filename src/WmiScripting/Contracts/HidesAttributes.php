<?php

namespace PhpWinTools\WmiScripting\Contracts;

interface HidesAttributes extends HasAttributes
{
    public function mergeHiddenAttributes(array $hidden_attributes, bool $merge_hidden = true);

    public function getHiddenAttributes();

    public function isHidden($attribute): bool;
}
