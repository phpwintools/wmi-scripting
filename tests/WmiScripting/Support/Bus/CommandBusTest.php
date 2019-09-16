<?php

namespace Tests\WmiScripting\Support\Bus;

use Closure;
use Tests\TestCase;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Support\Bus\CommandBus;
use PhpWinTools\WmiScripting\Support\Bus\Events\Event;
use PhpWinTools\WmiScripting\Support\Bus\CommandHandler;
use PhpWinTools\WmiScripting\Support\Bus\Events\Listener;
use PhpWinTools\WmiScripting\Support\Bus\Commands\Command;
use PhpWinTools\WmiScripting\Support\Bus\Events\EventHandler;
use PhpWinTools\WmiScripting\Support\Bus\Events\CommandBusPreEvent;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\PreCommandMiddleware;

class CommandBusTest extends TestCase
{
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
            ->registerMiddleware($closure, get_class($command));

        $expected = [
            'middleware',
            'closure',
            'command',
        ];

        $this->assertSame(
            $expected, $bus->handle($command)->processed, 'Failed to fire wildcard middleware before named middleware'
        );
    }

    /** @test */
    public function it_takes_a_closure_as_valid_middleware()
    {

    }

    /** @test */
    public function it_fires_an_event_before_any_pre_middleware()
    {
        $eventHandler = new EventHandler();

        $listener = new class extends Listener {
            public $reacted = false;

            public function react(Event $event)
            {
                $this->reacted = true;
            }
        };

        $eventHandler->subscribe(CommandBusPreEvent::class, $listener);

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
            public $name = '*';
            public function handle($subject, Closure $next)
            {
                $subject->processed[] = $this->name;
                return parent::handle($subject, $next);
            }
        };

        $second_middleware = new class extends PreCommandMiddleware {
            public $name = 'named';
            public function handle($subject, Closure $next)
            {
                $subject->processed[] = $this->name;
                return parent::handle($subject, $next);
            }
        };

        $bus = (new CommandBus())
            ->assignHandler(get_class($command), $handler)
            ->registerMiddleware(get_class($first_middleware), '*')
            ->registerMiddleware(get_class($second_middleware), get_class($command));

        $bus->handle($command);

        $this->assertTrue($listener->reacted, 'Listener did not react to command event');
    }
}
