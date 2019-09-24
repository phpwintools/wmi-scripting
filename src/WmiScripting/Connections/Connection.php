<?php

namespace PhpWinTools\WmiScripting\Connections;

interface Connection
{
    public function query($query, $model, $relationships);
}
