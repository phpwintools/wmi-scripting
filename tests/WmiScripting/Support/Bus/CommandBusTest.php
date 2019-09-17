<?php

namespace Tests\WmiScripting\Support\Bus;

use Closure;
use Tests\TestCase;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Support\Events\Event;
use PhpWinTools\WmiScripting\Support\Bus\CommandBus;
use PhpWinTools\WmiScripting\Support\Events\Listener;
use PhpWinTools\WmiScripting\Support\Bus\CommandHandler;
use PhpWinTools\WmiScripting\Support\Bus\Commands\Command;
use PhpWinTools\WmiScripting\Support\Bus\Events\PreMiddlewareStarted;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\PreCommandMiddleware;

class CommandBusTest extends TestCase
{
    /** @var Config */
    protected $config;

    /** @var CommandBus */
    protected $bus;

    protected function setUp()
    {
        parent::setUp();

        $this->config = Config::newInstance();

        $this->bus = $this->config->commandBus();
    }

    /** @test */
    public function it_can_instantiate()
    {
        $config = new Config();
        $bus = new CommandBus();

        $this->assertInstanceOf(CommandBus::class, $bus);
        $this->assertSame($config, $bus->getConfig());
        $this->assertSame(CommandBus::instance(), $bus);
    }

    /** @test */
    public function it_sends_commands_to_assigned_handler()
    {
        $command = $this->makeCommand();
        $handler = $this->makeCommandHandler();

        $this->bus->assignHandler(get_class($command), $handler);

        $this->assertEmpty($command->order);
        $this->assertSame(['command'], $this->bus->handle($command)->order);
    }

    /** @test */
    public function it_runs_pre_middleware_before_the_handler()
    {
        $command = $this->makeCommand();
        $handler = $this->makeCommandHandler();

        $first_middleware = new class extends PreCommandMiddleware {
            public $name = 'first';
            public function handle($subject, Closure $next)
            {
                $subject->order[] = $this->name;
                return parent::handle($subject, $next);
            }
        };

        $second_middleware = new class extends PreCommandMiddleware {
            public $name = 'second';
            public function handle($subject, Closure $next)
            {
                $subject->order[] = $this->name;
                return parent::handle($subject, $next);
            }
        };

        $after_middleware = new class extends PreCommandMiddleware {
            public $name = 'first after';
            public function handle($subject, Closure $next)
            {
                $result = parent::handle($subject, $next);
                $subject->order[] = $this->name;
                return $result;
            }
        };

        $this->bus->assignHandler(get_class($command), $handler)
            ->registerMiddleware(get_class($after_middleware), get_class($command))
            ->registerMiddleware(get_class($first_middleware), get_class($command))
            ->registerMiddleware(get_class($second_middleware), get_class($command));

        $this->assertEquals(['first', 'second', 'first after', 'command'], $this->bus->handle($command)->order);
    }

    /** @test */
    public function it_can_accept_a_closure_as_middleware()
    {
        $command = $this->makeCommand();
        $handler = $this->makeCommandHandler();

        $middleware = new class extends PreCommandMiddleware {
            public $name = 'middleware';
            public function handle($subject, Closure $next)
            {
                $subject->order[] = $this->name;
                return parent::handle($subject, $next);
            }
        };

        $closure = function ($subject, Closure $next) {
            $subject->order[] = 'closure';
            return $next($subject);
        };

        $this->bus->assignHandler(get_class($command), $handler)
            ->registerMiddleware(get_class($middleware), get_class($command))
            ->registerMiddleware($closure, get_class($command), PreCommandMiddleware::class);

        $this->assertSame(['middleware', 'closure', 'command'], $this->bus->handle($command)->order);
    }

    /** @test */
    public function it_fires_an_event_before_any_pre_middleware()
    {
        $command = $this->makeCommand();

        $middleware = new class extends PreCommandMiddleware {
            public function handle($command, Closure $next)
            {
                $command->order[] = 'middleware';
                return parent::handle($command, $next);
            }
        };

        $this->bus->assignHandler(get_class($command), $this->makeCommandHandler())
            ->registerMiddleware(get_class($middleware), get_class($command));

        $this->config->eventProvider()
            ->subscribe(PreMiddlewareStarted::class, $this->makeListener('listener', $command));

        $this->assertSame(['listener', 'middleware', 'command'], $this->bus->handle($command)->order);
    }

    protected function makeCommand()
    {
        return new class extends Command {
            public $order = [];
        };
    }

    protected function makeCommandHandler()
    {
        return new class extends CommandHandler {
            public function handle(Command $command)
            {
                $command->order[] = 'command';
                return $command;
            }
        };
    }

    protected function makeListener($name, $command)
    {
        return new class($command, $name) extends Listener {
            public $command;

            public $name;

            public function __construct($command, $name)
            {
                $this->command = $command;
                $this->name = $name;
            }

            public function react(Event $event)
            {
                $this->command->order[] = $this->name;
            }
        };
    }
}
