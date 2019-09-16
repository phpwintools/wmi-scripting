<?php

namespace PhpWinTools\WmiScripting\Support\Bus\Middleware;

use Closure;

class PostCommandMiddleware extends CommandMiddleware
{
    public function handle($handled_result, Closure $next)
    {
        return parent::handle($handled_result, $next);
    }
}
