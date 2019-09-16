<?php

namespace PhpWinTools\WmiScripting\Support\Bus;

use PhpWinTools\WmiScripting\Support\Bus\Commands\Command;

abstract class CommandHandler
{
    protected $failure_result = null;

    abstract public function handle(Command $command);

    public function getFailureResult()
    {
        return $this->failure_result;
    }
}
