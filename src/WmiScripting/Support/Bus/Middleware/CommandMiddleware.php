<?php

namespace PhpWinTools\WmiScripting\Support\Bus\Middleware;

use Closure;
use PhpWinTools\WmiScripting\Support\Bus\Commands\Command;

class CommandMiddleware
{
    protected $originalCommand;

    protected $command;

    protected $results;

    protected $next;

    public function resolve(Command $command, Closure $next)
    {
        return $next($command);
    }
}
