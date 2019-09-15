<?php

namespace PhpWinTools\WmiScripting\Support\Bus;

use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Exceptions\InvalidArgumentException;
use PhpWinTools\WmiScripting\Support\Bus\Commands\Command;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\CommandMiddleware;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\FailureCommandMiddleware;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\MiddlewareProcessor;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\PreCommandMiddleware;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\SuccessCommandMiddleware;

class CoreBus extends CommandBus
{
    /** @var self|null */
    protected static $instance = null;

    protected $config;

    /** @var array|CommandHandler[][] */
    protected $bus;

    protected $pre_middleware = [];

    protected $post_middleware = [];

    protected $success_middleware = [];

    protected $failure_middleware = [];

    protected $parent = null;

    protected $children = [];

    public function __construct(Config $config = null, CommandBus $parent = null)
    {
        $this->config = $config ?? Config::instance();
        $this->parent = $parent;

        static::$instance = $this;
    }

    public static function instance(Config $config = null)
    {
        return static::$instance ?? new static($config ?? Config::instance());
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function assignHandler($command, CommandHandler $commandHandler, CommandBus $bus = null)
    {
        if ($this->doesNotExtendCommand($command)) {
            throw new InvalidArgumentException("{$command} must extend " . Command::class);
        }

        if (($bus = $bus ?? $this) !== $this) {
            $this->children[get_class($bus)] = $bus;
            return $bus->assignHandler($command, $commandHandler);
        }

        $this->bus[$command] = $commandHandler;

        return $this;
    }

    public function registerMiddleware($middleware, $command, CommandBus $bus = null)
    {
        if ($this->doesNotExtendCommand($command)) {
            throw new InvalidArgumentException("{$command} must extend " . Command::class);
        }

        if ($this->doesNotExtendMiddleware($middleware)) {
            throw new InvalidArgumentException("{$middleware} must extend " . CommandMiddleware::class);
        }

        if (($bus = $bus ?? $this) !== $this) {
            $this->children[get_class($bus)] = $bus;
            return $bus->registerMiddleware($command, $middleware);
        }

        $parents = class_parents($middleware);

        if (array_key_exists(PreCommandMiddleware::class, $parents)) {
            $this->pre_middleware[$command] = $middleware;

            return $this;
        }

        if (array_key_exists(SuccessCommandMiddleware::class, $parents)) {
            $this->success_middleware[$command] = $middleware;

            return $this;
        }

        if (array_key_exists(FailureCommandMiddleware::class, $parents)) {
            $this->failure_middleware[$command] = $middleware;

            return $this;
        }

        $this->post_middleware[$command] = $middleware;

        return $this;
    }

    public function handle(Command $command)
    {
        // Pre-Middleware -> Post-Middleware (Any, Success, Failure)
        // Core: Middleware -> (Inner Bus -> Inner Middleware) -> CommandHandler - (Inner Bus -> CommandHandler) -> Middleware

        MiddlewareProcessor::process($this->pre_middleware, $command);

        return $this;
    }

    protected function process($stack, $method, Command $command)
    {

    }

    protected function extendsCommand($command)
    {
        return $this->classExtends($command, Command::class);
    }

    protected function doesNotExtendCommand($command)
    {
        return $this->extendsCommand($command) === false;
    }

    protected function extendsMiddleware($middleware)
    {
        return $this->classExtends($middleware, CommandMiddleware::class);
    }

    protected function doesNotExtendMiddleware($middleware)
    {
        return $this->extendsMiddleware($middleware) === false;
    }

    protected function classExtends($class, $parent)
    {
        return array_key_exists($parent, class_parents($class));
    }
}
