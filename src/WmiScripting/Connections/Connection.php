<?php

namespace PhpWinTools\WmiScripting\Connections;

interface Connection
{
    public function execQuery($query, $model, $relationships);
}
