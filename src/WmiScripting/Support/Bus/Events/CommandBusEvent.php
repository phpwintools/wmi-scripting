<?php

namespace PhpWinTools\WmiScripting\Support\Bus\Events;

use PhpWinTools\WmiScripting\Support\Bus\CommandBus;
use PhpWinTools\WmiScripting\Support\Bus\Commands\Command;
use PhpWinTools\WmiScripting\Support\Bus\Context;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\CommandMiddleware;

abstract class CommandBusEvent extends Event
{
    protected $bus;

    protected $command;

    protected $middleware;

    public function __construct(CommandBus $bus, Command $command = null, CommandMiddleware $middleware = null)
    {
        $this->bus = $bus;
        $this->command = $command;
        $this->middleware = $middleware;

        parent::__construct((new Context())
                ->add('bus', $this->bus)->add('command', $this->command)->add('middleware', $this->middleware));
    }

    public function bus()
    {
        return $this->bus;
    }

    public function command()
    {
        return $this->command;
    }

    public function middleware()
    {
        return $this->middleware;
    }
}
