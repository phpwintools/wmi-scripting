<?php

namespace Tests\WmiScripting\Support\Bus;

use Closure;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\PreCommandMiddleware;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\PostCommandMiddleware;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\FailureCommandMiddleware;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\SuccessCommandMiddleware;

class CommandBusMiddlewareTest extends CommandBusTest
{
    /** @test */
    public function it_can_accept_a_closure_as_pre_middleware()
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
    public function it_can_accept_a_closure_as_post_middleware()
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
            ->registerMiddleware($closure, get_class($command), PostCommandMiddleware::class);

        $this->assertSame(['middleware', 'command', 'closure'], $this->bus->handle($command)->order);
    }

    /** @test */
    public function it_can_accept_a_closure_as_success_middleware()
    {
        $command = $this->makeCommand();
        $handler = $this->makeCommandHandler();

        $pre = new class extends PreCommandMiddleware {
            public $name = 'pre';
            public function handle($subject, Closure $next)
            {
                $subject->order[] = $this->name;
                return parent::handle($subject, $next);
            }
        };

        $post = new class extends PostCommandMiddleware {
            public $name = 'post';
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
            ->registerMiddleware(get_class($post), get_class($command))
            ->registerMiddleware($closure, get_class($command), SuccessCommandMiddleware::class)
            ->registerMiddleware(get_class($pre), get_class($command));

        $this->assertSame(['pre', 'command', 'post', 'closure'], $this->bus->handle($command)->order);
    }

    /** @test */
    public function it_can_accept_a_closure_as_failure_middleware()
    {
        $command = $this->makeCommand();
        $handler = $this->makeCommandHandler('failed', true);

        $pre = new class extends PreCommandMiddleware {
            public $name = 'pre';
            public function handle($subject, Closure $next)
            {
                $subject->order[] = $this->name;
                return parent::handle($subject, $next);
            }
        };

        $post = new class extends PostCommandMiddleware {
            public $name = 'post';
            public function handle($subject, Closure $next)
            {
                $subject->order[] = $this->name;
                return parent::handle($subject, $next);
            }
        };

        $success = function ($subject, Closure $next) {
            $subject->order[] = 'success';
            return $next($subject);
        };

        $failure = function ($subject, Closure $next) {
            $subject->order[] = 'failure';
            return $next($subject);
        };

        $this->bus->assignHandler(get_class($command), $handler)
            ->registerMiddleware(get_class($post), get_class($command))
            ->registerMiddleware($failure, get_class($command), FailureCommandMiddleware::class)
            ->registerMiddleware($success, get_class($command), SuccessCommandMiddleware::class)
            ->registerMiddleware(get_class($pre), get_class($command));

        $this->assertSame(['pre', 'command', 'post', 'failure'], $this->bus->handle($command)->order);
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
}
