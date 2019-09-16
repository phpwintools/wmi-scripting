<?php

namespace PhpWinTools\WmiScripting\Support\Bus\Middleware;

use Closure;
use PhpWinTools\WmiScripting\Support\Bus\Commands\Command;

class MiddlewareProcessor
{
    protected static $instance;

    protected $fired = [];

    public function __construct()
    {
        static::$instance = $this;
    }

    public static function instance()
    {
        return static::$instance ?? static::$instance = new static();
    }

    /**
     * @param array|CommandMiddleware[] $middleware_array
     * @param Command                   $command
     * @param Closure|null              $core
     *
     * @return Command
     */
    public static function process(array $middleware_array, Command $command, Closure $core = null)
    {
        $processor = static::instance();
        $core = $core ?? function (Command $command) {
            dump('core');
            return $command;
        };

        $middleware_array = array_reverse($middleware_array);

        $stack = array_reduce($middleware_array, function ($next, $middleware) use ($command, $processor) {
            /** @var CommandMiddleware $middleware */
            $middleware = new $middleware();

            return function ($command) use ($next, $middleware, $processor) {
                $processor->fired[] = new FiredMiddleware($middleware, $command);
                return $middleware->handle($command, $next);
            };
        }, $core);

        return $stack($command);
    }

    public function fired()
    {
        return $this->fired;
    }
}
