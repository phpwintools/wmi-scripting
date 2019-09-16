<?php

namespace PhpWinTools\WmiScripting\Support\Bus\Middleware;

use Closure;

class CommandMiddleware
{
    public function handle($subject, Closure $next)
    {
        return $next($subject);
    }
}
