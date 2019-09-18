<?php

namespace PhpWinTools\WmiScripting\Support\Bus\Events;

use PhpWinTools\WmiScripting\Support\Events\Event;
use PhpWinTools\WmiScripting\Support\Bus\CommandBus;
use PhpWinTools\WmiScripting\Support\Events\Context;

class CommandBusEvent extends Event
{
    protected $bus;

    protected $command;

    public function __construct(CommandBus $bus, $command = null)
    {
        $this->bus = $bus;
        $this->command = $command;

        parent::__construct((new Context())
                ->add('bus', $this->bus)->add('subject', $this->command));
    }
}
