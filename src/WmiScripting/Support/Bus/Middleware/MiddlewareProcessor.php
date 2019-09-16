<?php

namespace PhpWinTools\WmiScripting\Support\Bus\Middleware;

use Closure;

class MiddlewareProcessor
{
    /**
     * @param array|CommandMiddleware[] $middleware_array
     * @param mixed                     $subject
     * @param Closure|null              $core
     *
     * @return mixed
     */
    public static function process(array $middleware_array, $subject, Closure $core = null)
    {
        $core = $core ?? function ($subject) {
            return $subject;
        };

        $stack = array_reduce(array_reverse($middleware_array), function ($next, $middleware) use ($subject) {
            /** @var CommandMiddleware $middleware */
            $middleware = new $middleware();

            return function ($command) use ($next, $middleware) {
                return $middleware->handle($command, $next);
            };
        }, $core);

        return $stack($subject);
    }
}
