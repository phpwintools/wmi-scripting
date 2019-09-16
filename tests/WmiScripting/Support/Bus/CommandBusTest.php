<?php

namespace Tests\WmiScripting\Support\Bus;

use Closure;
use Tests\TestCase;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Support\Bus\CommandBus;
use PhpWinTools\WmiScripting\Support\Bus\CommandHandler;
use PhpWinTools\WmiScripting\Support\Bus\Commands\Command;
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
}
