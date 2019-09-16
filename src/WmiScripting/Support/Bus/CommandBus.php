<?php

namespace PhpWinTools\WmiScripting\Support\Bus;

use Closure;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Support\Bus\Commands\Command;
use PhpWinTools\WmiScripting\Exceptions\InvalidArgumentException;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\CommandMiddleware;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\MiddlewareProcessor;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\PreCommandMiddleware;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\PostCommandMiddleware;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\FailureCommandMiddleware;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\SuccessCommandMiddleware;

class CommandBus
{
    /** @var self|null */
    protected static $instance = null;

    /** @var Config */
    protected $config;

    /** @var array|CommandHandler[] */
    protected $handlers = [];

    /** @var array|PreCommandMiddleware[] */
    protected $pre_middleware = [];

    /** @var array|PostCommandMiddleware[] */
    protected $post_middleware = [];

    /** @var array|SuccessCommandMiddleware[] */
    protected $success_middleware = [];

    /** @var array|FailureCommandMiddleware[] */
    protected $failure_middleware = [];

    public function __construct(Config $config = null)
    {
        $this->config = $config ?? Config::instance();

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

    public function assignHandler($command, CommandHandler $commandHandler)
    {
        if ($this->doesNotExtendCommand($command)) {
            throw new InvalidArgumentException("{$command} must extend " . Command::class);
        }

        $this->handlers[$command] = $commandHandler;

        return $this;
    }

    public function registerMiddleware($middleware, $command)
    {
        if ($this->doesNotExtendCommand($command)) {
            throw new InvalidArgumentException("{$command} must extend " . Command::class);
        }

        if ($this->doesNotExtendMiddleware($middleware)) {
            throw new InvalidArgumentException("{$middleware} must extend " . CommandMiddleware::class);
        }

        $parents = class_parents($middleware);

        if (array_key_exists(PreCommandMiddleware::class, $parents)) {
            $this->pre_middleware[$command][] = $middleware;

            return $this;
        }

        if (array_key_exists(SuccessCommandMiddleware::class, $parents)) {
            $this->success_middleware[$command][] = $middleware;

            return $this;
        }

        if (array_key_exists(FailureCommandMiddleware::class, $parents)) {
            $this->failure_middleware[$command][] = $middleware;

            return $this;
        }

        $this->post_middleware[$command][] = $middleware;

        return $this;
    }

    public function handle(Command $command, Closure $core_callback = null)
    {
        $command_name = get_class($command);

        $pre = $this->processMiddleware($this->pre_middleware[$command_name] ?? [], $command, $core_callback);
        $command_failure = true;
        $result = null;

        if (array_key_exists($command_name, $this->handlers)) {
            $handler = $this->handlers[$command_name];
            $result = $handler->handle($pre);
            $command_failure = $handler->getFailureResult() === $result;
        }

        $result = $this->processMiddleware($this->post_middleware[$command_name] ?? [], $result, $core_callback);

        if ($command_failure) {
            $result = $this->processMiddleware($this->failure_middleware[$command_name] ?? [], $result, $core_callback);
        } else {
            $result = $this->processMiddleware($this->success_middleware[$command_name] ?? [], $result, $core_callback);
        }

        return $result;
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

    protected function processMiddleware(array $stack, $subject, Closure $core_callback = null)
    {
        return MiddlewareProcessor::process($stack, $subject, $core_callback);
    }

    protected function classExtends($class, $parent)
    {
        return array_key_exists($parent, class_parents($class));
    }
}
