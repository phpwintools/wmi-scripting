<?php

namespace PhpWinTools\WmiScripting\Support\Bus\Events;

use PhpWinTools\WmiScripting\Support\Bus\CommandBus;
use PhpWinTools\WmiScripting\Support\Bus\CommandHandler;
use PhpWinTools\WmiScripting\Support\Bus\Commands\Command;

class CommandHandlerEvent extends CommandBusEvent
{
    public function __construct(CommandBus $bus, Command $command, CommandHandler $commandHandler)
    {
        parent::__construct($bus, $command);

        $this->payload->add('handler', $commandHandler);
    }
}
