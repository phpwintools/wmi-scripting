<?php

namespace PhpWinTools\WmiScripting\Support\Bus\Middleware;

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
     *
     * @return Command
     */
    public static function process(array $middleware_array, Command $command)
    {
        $processor = static::instance();

        array_map(function ($middleware) use (&$command, $processor) {
            /** @var string|CommandMiddleware $middleware */
            $middleware = new $middleware();
            $processor->fired[get_class($middleware)][] = new FiredMiddleware($middleware, $command);
            $command = $middleware->resolve($command, function () {
                //
            });
        }, $middleware_array);

        return $command;
    }
}
