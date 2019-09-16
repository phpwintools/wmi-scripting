<?php

namespace PhpWinTools\WmiScripting\Support\Bus\Middleware;

use Closure;

class PreCommandMiddleware extends CommandMiddleware
{
    public function handle($command, Closure $next)
    {
        return parent::handle($command, $next);
    }
}
