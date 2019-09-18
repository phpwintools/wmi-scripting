<?php

namespace PhpWinTools\WmiScripting\Support\Bus\Middleware;

use Closure;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Exceptions\InvalidArgumentException;
use PhpWinTools\WmiScripting\Support\Bus\Events\CommandMiddlewareEvent;

class MiddlewareStack
{
    protected $config;

    protected $pre_event_class;

    protected $post_event_class;

    protected $stack = [];

    public function __construct(Config $config = null, $pre_event_class = null, $post_event_class = null)
    {
        $this->config = $config ?? Config::instance();
        $this->pre_event_class = $this->validateEventClass($pre_event_class);
        $this->post_event_class = $this->validateEventClass($post_event_class);
    }

    public function add($middleware, string $subject)
    {
        $this->stack[$subject][] = $middleware;

        return $this;
    }

    public function get($subject)
    {
        $subject = is_object($subject) ? get_class($subject) : $subject;

        $wildcard = $this->prep($this->stack['*'] ?? []);

        return array_merge($this->prep($this->stack[$subject] ?? []), $wildcard);
    }

    public function process($subject, Closure $core = null)
    {
        if ($this->pre_event_class) {
            $this->fireEvent(new $this->pre_event_class($this->config->commandBus(), $subject));
        }

        $core = $core ?? function ($subject) {
            return $subject;
        };

        $stack = array_reduce($this->get($subject), function ($next, $middleware) use ($subject) {
            if ($middleware instanceof Closure) {
                return function ($command) use ($next, $middleware) {
                    return $middleware($command, $next);
                };
            }

            /** @var CommandMiddleware $middleware */
            $middleware = new $middleware();

            return function ($command) use ($next, $middleware) {
                return $middleware->handle($command, $next);
            };
        }, $core);

        $result = $stack($subject);

        if ($this->post_event_class) {
            $this->fireEvent(new $this->post_event_class($this->config->commandBus(), $subject));
        }

        return $result;
    }

    protected function fireEvent(CommandMiddlewareEvent $event)
    {
        $this->config->eventProvider()->fire($event);
    }

    protected function validateEventClass($event_class)
    {
        if (!array_key_exists(CommandMiddlewareEvent::class, class_parents($event_class))) {
            throw new InvalidArgumentException('Events must extend ' . CommandMiddlewareEvent::class);
        }

        return $event_class;
    }

    protected function prep(array $stack)
    {
        return array_reverse($stack);
    }
}
