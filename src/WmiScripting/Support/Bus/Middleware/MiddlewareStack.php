<?php

namespace PhpWinTools\WmiScripting\Support\Bus\Middleware;

use Closure;

class MiddlewareStack
{
    protected $stack = [];

    public function add($middleware, string $subject)
    {
        $this->stack[$subject][] = $middleware;

        return $this;
    }

    public function all()
    {
        return $this->prep($this->stack);
    }

    public function get($subject)
    {
        $subject = is_object($subject) ? get_class($subject) : $subject;

        $wildcard = $this->prep($this->stack['*'] ?? []);

        return array_merge($this->prep($this->stack[$subject] ?? []), $wildcard);
    }

    public function has($subject)
    {
        return empty($this->get($subject));
    }

    public function process($subject, Closure $core = null)
    {
        $core = $core ?? function ($subject) {
                return $subject;
        };

        $stack = array_reduce($this->get($subject), function ($next, $middleware) use ($subject) {
            if ($middleware instanceof Closure) {
                return function ($command) use ($next, $middleware) {
                    return $middleware($command, $next);
                };
            }

            /** @var CommandMiddleware $middleware */
            $middleware = new $middleware();

            return function ($command) use ($next, $middleware) {
                return $middleware->handle($command, $next);
            };
        }, $core);

        return $stack($subject);
    }

    protected function prep(array $stack)
    {
        return array_reverse($stack);
    }
}
