<?php

namespace PhpWinTools\WmiScripting\Testing\CallStacks;

use PhpWinTools\WmiScripting\Support\ComVariantWrapper;
use PhpWinTools\WmiScripting\Collections\ArrayCollection;
use PhpWinTools\WmiScripting\Support\ApiObjects\AbstractWbemObject;

class ComCallStack
{
    protected static $instance;

    /** @var array|ComCall[] */
    protected $stack;

    public function __construct()
    {
        $this->stack = new ArrayCollection();

        static::$instance = $this;
    }

    /**
     * @return ComCallStack
     */
    public static function instance()
    {
        return static::$instance ?? new static();
    }

    public static function current()
    {
        return static::instance()->getCurrent();
    }

    public function getStackCollection()
    {
        return $this->stack;
    }

    public function add(...$call)
    {
        $method = null;
        $arguments = [];

        switch ($type = array_shift($call)) {
            case '__set':
            case '__call':
                $method = array_shift($call);
                $arguments = $call[0];
                break;
            case '__get':
            default:
                if (is_array($call)) {
                    $method = array_shift($call);
                    $arguments = $call[0] ?? [];
                } else {
                    $method = $call;
                    $arguments = [];
                }

                break;
        }

        $backtrace = $this->getBacktrace();
        $this->stack->add(new ComCall(
            $type,
            $method,
            $arguments,
            $backtrace['responder'],
            $backtrace['caller'],
            $backtrace['detected_caller']
        ));

        return $this;
    }

    public function getCurrent()
    {
        return $this->stack[count($this->stack) - 1];
    }

    protected function getBacktrace()
    {
        $backtrace = new ArrayCollection(debug_backtrace(true));
        $wrapper_call_key = key($backtrace->filter(function ($trace, $key) {
            if ($trace['class'] === ComVariantWrapper::class) {
                return $key;
            }

            return false;
        })->toArray());

        if ($wrapper_call_key) {
            $found_detected = false;
            $backtrace = $backtrace->filter(function ($trace, $key) use ($wrapper_call_key, &$found_detected) {
                if (!$found_detected
                    && $key > $wrapper_call_key + 1
                    && array_key_exists(AbstractWbemObject::class, class_parents($trace['class']))
                ) {
                    return $found_detected = true;
                }
                return $key === $wrapper_call_key || $key === $wrapper_call_key + 1;
            })->values()->map(function ($trace) {
                return new ComTraceSubject(
                    $trace['file'],
                    $trace['class'],
                    $trace['function'],
                    $trace['args'],
                    isset($trace['object']) ? get_class($trace['object']) : null
                );
            });

            return [
                'responder'         => $backtrace[0],
                'caller'            => $backtrace[1] ?? null,
                'detected_caller'   => $backtrace[2] ?? null,
            ];
        }

        return [
            'responder'         => null,
            'caller'            => null,
            'detected_caller'   => null,
        ];
    }
}
