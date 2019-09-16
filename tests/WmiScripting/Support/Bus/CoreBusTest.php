<?php

namespace Tests\WmiScripting\Support\Bus;

use Closure;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Support\Bus\CommandBus;
use PhpWinTools\WmiScripting\Support\Bus\Commands\Command;
use PhpWinTools\WmiScripting\Support\Bus\CoreBus;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\MiddlewareProcessor;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\PreCommandMiddleware;
use Tests\TestCase;

class CoreBusTest extends TestCase
{
    /** @test */
    public function it_can_instantiate()
    {
        $config = new Config();
        $bus = new CoreBus();

        $this->assertInstanceOf(CoreBus::class, $bus);
        $this->assertSame($config, $bus->getConfig());
        $this->assertSame(CoreBus::instance(), $bus);
    }

    /** @test */
    public function it_runs_middleware_before_command()
    {
        $command = new class extends Command {};

        $middleware = new class extends PreCommandMiddleware {
            public static $instance;

            public $has_run_instance = false;

            public function __construct()
            {
                self::$instance = $this;
            }

            public static function instance()
            {
                return self::$instance ?? self::$instance = new self();
            }

            public function resolve(Command $command, Closure $next)
            {
                $this->has_run_instance = true;
                return $next($command);
            }
        };

        $bus = CoreBus::instance()->registerMiddleware(get_class($middleware), get_class($command));
        $bus->handle($command);
        dd(MiddlewareProcessor::instance()->fired());
    }
}
