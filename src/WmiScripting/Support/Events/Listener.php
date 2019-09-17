<?php

namespace PhpWinTools\WmiScripting\Support\Events;

abstract class Listener
{
    abstract public function react(Event $event);
}
