<?php

namespace Tests\WmiScripting\Support\Bus;

use Closure;
use PhpWinTools\WmiScripting\Support\Bus\Events\PreMiddlewareEnded;
use PhpWinTools\WmiScripting\Support\Bus\Events\CommandHandlerEnded;
use PhpWinTools\WmiScripting\Support\Bus\Events\PostMiddlewareEnded;
use PhpWinTools\WmiScripting\Support\Bus\Events\PreMiddlewareStarted;
use PhpWinTools\WmiScripting\Support\Bus\Events\CommandHandlerStarted;
use PhpWinTools\WmiScripting\Support\Bus\Events\PostMiddlewareStarted;
use PhpWinTools\WmiScripting\Support\Bus\Events\FailureMiddlewareEnded;
use PhpWinTools\WmiScripting\Support\Bus\Events\SuccessMiddlewareEnded;
use PhpWinTools\WmiScripting\Support\Bus\Events\FailureMiddlewareStarted;
use PhpWinTools\WmiScripting\Support\Bus\Events\SuccessMiddlewareStarted;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\PreCommandMiddleware;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\PostCommandMiddleware;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\FailureCommandMiddleware;
use PhpWinTools\WmiScripting\Support\Bus\Middleware\SuccessCommandMiddleware;

class CommandBusEventTest extends CommandBusTest
{
    /** @test */
    public function it_fires_events_before_and_after_pre_middleware_but_before_command()
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
            ->subscribe(PreMiddlewareStarted::class, $this->makeListener('pre_started', $command))
            ->subscribe(PreMiddlewareEnded::class, $this->makeListener('pre_ended', $command));

        $this->assertSame(['pre_started', 'middleware', 'pre_ended', 'command'], $this->bus->handle($command)->order);
    }

    /** @test */
    public function it_fires_events_before_and_after_command_handle_but_before_post_middleware()
    {
        $command = $this->makeCommand();

        $pre = new class extends PreCommandMiddleware {
            public function handle($command, Closure $next)
            {
                $command->order[] = 'pre';
                return parent::handle($command, $next);
            }
        };

        $post = new class extends PostCommandMiddleware {
            public function handle($command, Closure $next)
            {
                $command->order[] = 'post';
                return parent::handle($command, $next);
            }
        };

        $this->bus->assignHandler(get_class($command), $this->makeCommandHandler())
            ->registerMiddleware(get_class($post), get_class($command))
            ->registerMiddleware(get_class($pre), get_class($command));

        $this->config->eventProvider()
            ->subscribe(CommandHandlerStarted::class, $this->makeListener('command_started', $command))
            ->subscribe(CommandHandlerEnded::class, $this->makeListener('command_ended', $command));

        $this->assertSame(
            ['pre', 'command_started', 'command', 'command_ended', 'post'], $this->bus->handle($command)->order
        );
    }

    /** @test */
    public function it_fires_events_before_and_after_post_middleware_but_before_success_middleware()
    {
        $command = $this->makeCommand();

        $post = new class extends PostCommandMiddleware {
            public function handle($command, Closure $next)
            {
                $command->order[] = 'post';
                return parent::handle($command, $next);
            }
        };

        $success = new class extends SuccessCommandMiddleware {
            public function handle($command, Closure $next)
            {
                $command->order[] = 'success';
                return parent::handle($command, $next);
            }
        };

        $this->bus->assignHandler(get_class($command), $this->makeCommandHandler())
            ->registerMiddleware(get_class($post), get_class($command))
            ->registerMiddleware(get_class($success), get_class($command));

        $this->config->eventProvider()
            ->subscribe(PostMiddlewareStarted::class, $this->makeListener('post_started', $command))
            ->subscribe(PostMiddlewareEnded::class, $this->makeListener('post_ended', $command));

        $this->assertSame(
            ['command', 'post_started', 'post', 'post_ended', 'success'], $this->bus->handle($command)->order
        );
    }

    /** @test */
    public function it_fires_events_before_and_after_post_middleware_but_before_failure_middleware()
    {
        $command = $this->makeCommand();

        $post = new class extends PostCommandMiddleware {
            public function handle($command, Closure $next)
            {
                $command->order[] = 'post';
                return parent::handle($command, $next);
            }
        };

        $success = new class extends FailureCommandMiddleware {
            public function handle($command, Closure $next)
            {
                $command->order[] = 'failure';
                return parent::handle($command, $next);
            }
        };

        $this->bus->assignHandler(get_class($command), $this->makeCommandHandler('failure', true))
            ->registerMiddleware(get_class($post), get_class($command))
            ->registerMiddleware(get_class($success), get_class($command));

        $this->config->eventProvider()
            ->subscribe(PostMiddlewareStarted::class, $this->makeListener('post_started', $command))
            ->subscribe(PostMiddlewareEnded::class, $this->makeListener('post_ended', $command));

        $this->assertSame(
            ['command', 'post_started', 'post', 'post_ended', 'failure'], $this->bus->handle($command)->order
        );
    }

    /** @test */
    public function it_fires_events_before_and_after_failure_middleware()
    {
        $command = $this->makeCommand();

        $success = new class extends FailureCommandMiddleware {
            public function handle($command, Closure $next)
            {
                $command->order[] = 'failure';
                return parent::handle($command, $next);
            }
        };

        $this->bus->assignHandler(get_class($command), $this->makeCommandHandler('failure', true))
            ->registerMiddleware(get_class($success), get_class($command));

        $this->config->eventProvider()
            ->subscribe(FailureMiddlewareStarted::class, $this->makeListener('failure_started', $command))
            ->subscribe(FailureMiddlewareEnded::class, $this->makeListener('failure_ended', $command));

        $this->assertSame(
            ['command', 'failure_started', 'failure', 'failure_ended'], $this->bus->handle($command)->order
        );
    }

    /** @test */
    public function it_fires_events_before_and_after_success_middleware()
    {
        $command = $this->makeCommand();

        $success = new class extends SuccessCommandMiddleware {
            public function handle($command, Closure $next)
            {
                $command->order[] = 'success';
                return parent::handle($command, $next);
            }
        };

        $this->bus->assignHandler(get_class($command), $this->makeCommandHandler())
            ->registerMiddleware(get_class($success), get_class($command));

        $this->config->eventProvider()
            ->subscribe(SuccessMiddlewareStarted::class, $this->makeListener('success_started', $command))
            ->subscribe(SuccessMiddlewareEnded::class, $this->makeListener('success_ended', $command));

        $this->assertSame(
            ['command', 'success_started', 'success', 'success_ended'], $this->bus->handle($command)->order
        );
    }
}
