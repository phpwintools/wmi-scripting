<?php

namespace PhpWinTools\WmiScripting\Support\Bus\Events;

use PhpWinTools\WmiScripting\Support\Bus\Context;
use PhpWinTools\WmiScripting\Support\Events\Event;
use PhpWinTools\WmiScripting\Support\Bus\CommandBus;
use PhpWinTools\WmiScripting\Support\Bus\Commands\Command;

abstract class CommandBusEvent extends Event
{
    protected $bus;

    protected $command;

    public function __construct(CommandBus $bus, Command $command = null)
    {
        $this->bus = $bus;
        $this->command = $command;

        parent::__construct((new Context())
                ->add('bus', $this->bus)->add('command', $this->command));
    }

    public function bus()
    {
        return $this->bus;
    }

    public function command()
    {
        return $this->command;
    }
}
