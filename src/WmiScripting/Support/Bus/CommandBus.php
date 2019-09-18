<?php

namespace PhpWinTools\WmiScripting\Support\Bus;

use Closure;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Support\Events\Event;
use PhpWinTools\WmiScripting\Support\Bus\Commands\Command;
use PhpWinTools\WmiScripting\Exceptions\InvalidArgumentException;
use PhpWinTools\WmiScripting\Support\Bus\Events\PreMiddlewareEnded;
use PhpWinTools\WmiScripting\Support\Bus\Events\CommandHandlerEnded;
use PhpWinTools\WmiScripting\Support\Bus\Events\PostMiddlewareEnded;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\MiddlewareStack;
use PhpWinTools\WmiScripting\Support\Bus\Events\PreMiddlewareStarted;
use PhpWinTools\WmiScripting\Support\Bus\Events\CommandHandlerStarted;
use PhpWinTools\WmiScripting\Support\Bus\Events\PostMiddlewareStarted;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\CommandMiddleware;
use PhpWinTools\WmiScripting\Support\Bus\Events\FailureMiddlewareEnded;
use PhpWinTools\WmiScripting\Support\Bus\Events\SuccessMiddlewareEnded;
use PhpWinTools\WmiScripting\Support\Bus\Events\FailureMiddlewareStarted;
use PhpWinTools\WmiScripting\Support\Bus\Events\SuccessMiddlewareStarted;
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

    /** @var MiddlewareStack */
    protected $preMiddleware;

    /** @var MiddlewareStack */
    protected $postMiddleware = [];

    /** @var MiddlewareStack */
    protected $failureMiddleware = [];

    /** @var MiddlewareStack */
    protected $successMiddleware = [];

    public function __construct(Config $config = null)
    {
        $this->config = $config ?? Config::instance();

        $this->bootMiddlewareStack();

        static::$instance = $this->config->registerProvider('bus', $this);
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
        if ($this->invalidCommand($command)) {
            throw new InvalidArgumentException("{$command} must extend " . Command::class);
        }

        $this->handlers[$command] = $commandHandler;

        return $this;
    }

    public function registerMiddleware($middleware, $command, string $stack = PostCommandMiddleware::class)
    {
        if ($this->invalidCommand($command)) {
            throw new InvalidArgumentException("{$command} must extend " . Command::class);
        }

        if ($this->invalidMiddleware($middleware)) {
            throw new InvalidArgumentException("{$middleware} must extend " . CommandMiddleware::class);
        }

        $parents = class_parents($middleware);

        if ($middleware instanceof Closure) {
            $parents = [$stack => $stack];
        }

        if (array_key_exists(PreCommandMiddleware::class, $parents)) {
            $this->preMiddleware->add($middleware, $command);

            return $this;
        }

        if (array_key_exists(SuccessCommandMiddleware::class, $parents)) {
            $this->successMiddleware->add($middleware, $command);

            return $this;
        }

        if (array_key_exists(FailureCommandMiddleware::class, $parents)) {
            $this->failureMiddleware->add($middleware, $command);

            return $this;
        }

        $this->postMiddleware->add($middleware, $command);

        return $this;
    }

    public function handle(Command $command, Closure $core_callback = null)
    {
        $result = null;
        $command_failure = true;
        $command_name = get_class($command);

        $pre = $this->preMiddleware->process($command, $core_callback);

        if (array_key_exists($command_name, $this->handlers)) {
            $handler = $this->handlers[$command_name];

            $this->fireEvent(new CommandHandlerStarted($this, $command, $handler));
            $result = $handler->handle($pre);
            $command_failure = $handler->getFailureResult() === $result;
            $this->fireEvent(new CommandHandlerEnded($this, $command, $handler));
        }

        $result = $this->postMiddleware->process($command, $core_callback);

        if ($command_failure) {
            $result = $this->failureMiddleware->process($result, $core_callback);
        } else {
            $result = $this->successMiddleware->process($result, $core_callback);
        }

        return $result;
    }

    protected function fireEvent(Event $event)
    {
        $this->getConfig()->eventProvider()->fire($event);
    }

    protected function bootMiddlewareStack()
    {
        $this->preMiddleware = new MiddlewareStack(
            $this->getConfig(),
            PreMiddlewareStarted::class,
            PreMiddlewareEnded::class
        );

        $this->postMiddleware = new MiddlewareStack(
            $this->getConfig(),
            PostMiddlewareStarted::class,
            PostMiddlewareEnded::class
        );

        $this->failureMiddleware = new MiddlewareStack(
            $this->getConfig(),
            FailureMiddlewareStarted::class,
            FailureMiddlewareEnded::class
        );

        $this->successMiddleware = new MiddlewareStack(
            $this->getConfig(),
            SuccessMiddlewareStarted::class,
            SuccessMiddlewareEnded::class
        );
    }

    protected function validCommand($command)
    {
        return $command = '*' || $this->classExtends($command, Command::class);
    }

    protected function invalidCommand($command)
    {
        return $this->validCommand($command) === false;
    }

    protected function validMiddleware($middleware)
    {
        return $middleware instanceof Closure || $this->classExtends($middleware, CommandMiddleware::class);
    }

    protected function invalidMiddleware($middleware)
    {
        return $this->validMiddleware($middleware) === false;
    }

    protected function classExtends($class, $parent)
    {
        return array_key_exists($parent, class_parents($class));
    }
}
