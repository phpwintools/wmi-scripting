<?php

namespace PhpWinTools\WmiScripting\Query;

class SystemBiosBuilder extends ComponentBuilder
{
    public function withComputerSystem()
    {
        return $this->withGroupComponent();
    }

    public function withBios()
    {
        return $this->withPartComponent();
    }
}
