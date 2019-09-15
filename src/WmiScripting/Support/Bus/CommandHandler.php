<?php

namespace PhpWinTools\WmiScripting\Support\Bus;

use PhpWinTools\WmiScripting\Support\Bus\Commands\Command;

abstract class CommandHandler
{
    abstract public function handle(Command $command);
}
