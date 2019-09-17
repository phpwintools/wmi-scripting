<?php

namespace PhpWinTools\WmiScripting\Support\Bus;

use Closure;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Support\Bus\Commands\Command;
use PhpWinTools\WmiScripting\Exceptions\InvalidArgumentException;
use PhpWinTools\WmiScripting\Support\Bus\Events\CommandHandlerEnded;
use PhpWinTools\WmiScripting\Support\Bus\Events\CommandHandlerStarted;
use PhpWinTools\WmiScripting\Support\Bus\Events\PreMiddlewareEnded;
use PhpWinTools\WmiScripting\Support\Bus\Events\CommandBusEvent;
use PhpWinTools\WmiScripting\Support\Bus\Events\PreMiddlewareStarted;
use PhpWinTools\WmiScripting\Support\Events\Event;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\MiddlewareStack;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\CommandMiddleware;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\PostCommandMiddleware;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\PreCommandMiddleware;
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
        $this->boot();

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
        $command_name = get_class($command);

        $this->firePreMiddlewareStartEvent($command);

        $pre = $this->preMiddleware->process($command, $core_callback);
        $command_failure = true;
        $result = null;

        $this->firePreMiddleEndEvent($command);

        if (array_key_exists($command_name, $this->handlers)) {
            $handler = $this->handlers[$command_name];

            $this->fireCommandHandlerStartEvent($command, $handler);

            $result = $handler->handle($pre);
            $command_failure = $handler->getFailureResult() === $result;

            $this->fireCommandHandlerEndEvent($command, $handler);
        }

        $result = $this->postMiddleware->process($command, $core_callback);

        if ($command_failure) {
            $result = $this->failureMiddleware->process($result, $core_callback);
        } else {
            $result = $this->successMiddleware->process($result, $core_callback);
        }

        return $result;
    }

    protected function firePreMiddlewareStartEvent(Command $command)
    {
        $this->fire(new PreMiddlewareStarted($this, $command));

        return $this;
    }

    protected function firePreMiddleEndEvent(Command $command)
    {
        $this->fire(new PreMiddlewareEnded($this, $command));

        return $this;
    }

    protected function fireCommandHandlerStartEvent(Command $command, CommandHandler $handler)
    {
        $this->fire(new CommandHandlerStarted($this, $command, $handler));

        return $this;
    }

    protected function fireCommandHandlerEndEvent(Command $command, CommandHandler $handler)
    {
        $this->fire(new CommandHandlerEnded($this, $command, $handler));

        return $this;
    }


    protected function fire(Event $event)
    {
        $this->events()->fire($event);
    }

    protected function events()
    {
        return $this->getConfig()->events();
    }

    protected function boot()
    {
        $this->preMiddleware = new MiddlewareStack();
        $this->postMiddleware = new MiddlewareStack();
        $this->failureMiddleware = new MiddlewareStack();
        $this->successMiddleware = new MiddlewareStack();
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
