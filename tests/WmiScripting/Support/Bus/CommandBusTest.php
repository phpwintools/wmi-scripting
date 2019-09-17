<?php

namespace Tests\WmiScripting\Support\Bus;

use Closure;
use PhpWinTools\WmiScripting\Support\Bus\Events\PreMiddlewareStarted;
use PhpWinTools\WmiScripting\Support\Events\EventHandler;
use Tests\TestCase;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Support\Events\Event;
use PhpWinTools\WmiScripting\Support\Bus\CommandBus;
use PhpWinTools\WmiScripting\Support\Events\Listener;
use PhpWinTools\WmiScripting\Support\Bus\CommandHandler;
use PhpWinTools\WmiScripting\Support\Bus\Commands\Command;
use PhpWinTools\WmiScripting\Support\Bus\Events\CommandBusEvent;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\PreCommandMiddleware;

class CommandBusTest extends TestCase
{
    /** @var Config */
    protected $config;

    protected function setUp()
    {
        parent::setUp();

        $this->config = Config::newInstance();
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
        $command = new class extends Command {
            public $processed = [];
        };

        $handler = new class extends CommandHandler {
            public function handle(Command $command)
            {
                $command->processed[] = 'command';
                return $command;
            }
        };

        $bus = (new CommandBus())->assignHandler(get_class($command), $handler);

        $this->assertEmpty($command->processed);
        $this->assertSame(['command'], $bus->handle($command)->processed);
    }

    /** @test */
    public function it_runs_pre_middleware_before_the_handler()
    {
        $command = new class extends Command {
            public $processed = [];
        };

        $handler = new class extends CommandHandler {
            public function handle(Command $command)
            {
                $command->processed[] = 'command';
                return $command;
            }
        };

        $first_middleware = new class extends PreCommandMiddleware {
            public $name = 'first';
            public function handle($subject, Closure $next)
            {
                $subject->processed[] = $this->name;
                return parent::handle($subject, $next);
            }
        };

        $second_middleware = new class extends PreCommandMiddleware {
            public $name = 'second';
            public function handle($subject, Closure $next)
            {
                $subject->processed[] = $this->name;
                return parent::handle($subject, $next);
            }
        };

        $after_middleware = new class extends PreCommandMiddleware {
            public $name = 'first after';
            public function handle($subject, Closure $next)
            {
                $result = parent::handle($subject, $next);
                $subject->processed[] = $this->name;
                return $result;
            }
        };


        $bus = (new CommandBus())
            ->assignHandler(get_class($command), $handler)
            ->registerMiddleware(get_class($after_middleware), get_class($command))
            ->registerMiddleware(get_class($first_middleware), get_class($command))
            ->registerMiddleware(get_class($second_middleware), get_class($command));

        $expected = [
            'first',
            'second',
            'first after',
            'command',
        ];

        $this->assertEquals($expected, $bus->handle($command)->processed);
    }

    /** @test */
    public function it_fires_wildcard_pre_middleware_first()
    {
        $command = new class extends Command {
            public $processed = [];
        };

        $handler = new class extends CommandHandler {
            public function handle(Command $command)
            {
                $command->processed[] = 'command';
                return $command;
            }
        };

        $middleware = new class extends PreCommandMiddleware {
            public $name = 'middleware';
            public function handle($subject, Closure $next)
            {
                $subject->processed[] = $this->name;
                return parent::handle($subject, $next);
            }
        };

        $closure = function ($subject, Closure $next) {
            $subject->processed[] = 'closure';
            return $next($subject);
        };

        $bus = (new CommandBus())
            ->assignHandler(get_class($command), $handler)
            ->registerMiddleware(get_class($middleware), get_class($command))
            ->registerMiddleware($closure, get_class($command), PreCommandMiddleware::class);

        $expected = [
            'middleware',
            'closure',
            'command',
        ];

        $this->assertSame($expected, $bus->handle($command)->processed);
    }

    /** @test */
    public function it_fires_an_event_before_any_pre_middleware()
    {
        $command = new class extends Command {};

        $handler = new class extends CommandHandler {
            public function handle(Command $command)
            {
                return $command;
            }
        };

        $middleware = new class extends PreCommandMiddleware {};

        $bus = (new CommandBus($this->config))
            ->assignHandler(get_class($command), $handler)
            ->registerMiddleware(get_class($middleware), get_class($command));

        $listener = new class extends Listener {
            public $reacted = false;

            public function react(Event $event)
            {
                $this->reacted = true;
            }
        };

        $this->config->trackEvents();
        /** TODO: All singletons need to register themselves with the Config */
        $events = $this->config->events();
        $events->subscribe(PreMiddlewareStarted::class, $listener);

        $this->assertFalse($listener->reacted);
        $this->assertFalse($events->history()->happened(CommandBusEvent::class));

        $bus->handle($command);

        $this->assertTrue($listener->reacted);
        $this->assertTrue($events->history()->happened(CommandBusEvent::class));
        $this->assertTrue($events->history()->happened(PreMiddlewareStarted::class));
    }
}
