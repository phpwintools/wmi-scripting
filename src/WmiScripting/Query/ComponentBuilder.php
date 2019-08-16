<?php

namespace PhpWinTools\WmiScripting\Query;

class ComponentBuilder extends Builder
{
    public function withGroupComponent()
    {
        return $this->with('GroupComponent');
    }

    public function withPartComponent()
    {
        return $this->with('PartComponent');
    }

    public function withAll()
    {
        return $this->withGroupComponent()->withPartComponent();
    }
}
