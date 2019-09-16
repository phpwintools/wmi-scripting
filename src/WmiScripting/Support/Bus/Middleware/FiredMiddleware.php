<?php

namespace PhpWinTools\WmiScripting\Support\Bus\Middleware;

use PhpWinTools\WmiScripting\Support\Bus\Commands\Command;

class FiredMiddleware
{
    protected $middleware;

    protected $command;

    public function __construct(CommandMiddleware $middleware, Command $command)
    {
        $this->command = $command;

        $this->middleware = $middleware;
    }

    public function command()
    {
        return $this->command;
    }

    public function commandType()
    {
        return get_class($this->command());
    }

    public function middleware()
    {
        return $this->middleware;
    }

    public function middlewareType()
    {
        return $this->middleware();
    }
}
