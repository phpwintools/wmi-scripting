<?php

namespace Tests\WmiScripting\Support\Bus;

use Tests\TestCase;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Support\Events\Event;
use PhpWinTools\WmiScripting\Support\Bus\CommandBus;
use PhpWinTools\WmiScripting\Support\Events\Listener;
use PhpWinTools\WmiScripting\Support\Bus\CommandHandler;
use PhpWinTools\WmiScripting\Support\Bus\Commands\Command;

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

    protected function makeCommand()
    {
        return new class extends Command {
            public $order = [];
        };
    }

    protected function makeCommandHandler($failure_result = null, bool $trigger_failure = false)
    {
        return new class($failure_result, $trigger_failure) extends CommandHandler {
            protected $failure_result;

            protected $trigger_failure = false;

            public function __construct($failure_result, $trigger_failure)
            {
                $this->failure_result = $failure_result;
                $this->trigger_failure = $trigger_failure;
            }

            public function handle(Command $command)
            {
                $command->order[] = 'command';

                if ($this->trigger_failure) {
                    return $this->failure_result;
                }

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
