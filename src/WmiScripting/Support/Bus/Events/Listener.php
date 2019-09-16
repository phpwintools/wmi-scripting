<?php

namespace PhpWinTools\WmiScripting\Support\Bus\Events;

abstract class Listener
{
    abstract public function react(Event $event);
}
