<?php

namespace PhpWinTools\WmiScripting\Support\Events;

class Event
{
    protected $payload;

    protected $called_class;

    public function __construct(Payload $payload)
    {
        $this->payload = $payload;
        $this->called_class = get_called_class();
    }

    public static function new(Payload $payload)
    {
        return new static($payload);
    }

    public function payload(): Payload
    {
        return $this->payload;
    }

    public function actual(): string
    {
        return $this->called_class;
    }
}
