<?php

namespace Tests\WmiScripting\Support\Event;

use Tests\TestCase;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Support\Events\Event;
use PhpWinTools\WmiScripting\Support\Events\Payload;
use PhpWinTools\WmiScripting\Support\Events\Listener;
use PhpWinTools\WmiScripting\Support\Events\EventProvider;
use PhpWinTools\WmiScripting\Support\Events\EventHistoryProvider;

class EventProviderTest extends TestCase
{
    /** @var EventProvider */
    protected $event;

    /** @var EventHistoryProvider */
    protected $history;

    protected function setUp()
    {
        $config = Config::newInstance();
        $this->event = $config->eventProvider();
        $this->history = $this->event->history();
    }

    /** @test */
    public function it_can_instantiate()
    {
        $this->assertInstanceOf(EventProvider::class, new EventProvider(new Config()));
        $this->assertInstanceOf(EventProvider::class, EventProvider::instance());
    }

    /** @test */
    public function it_registers_itself_with_the_config_on_instantiation()
    {
        $config = new Config();
        $configEvent = $config->getBoundProvider('event');

        $this->assertNotSame($configEvent, $newEvent = new EventProvider());
        $this->assertSame($newEvent, $config->getBoundProvider('event'));
    }

    /** @test */
    public function it_runs_listeners_when_an_applicable_event_is_fired()
    {
        $subject = new class {
            public $order = [];
        };

        $child = new class(new Payload()) extends Event {};

        $this->event->subscribe(get_class($child), $this->makeListener('listener', $subject));

        $this->event->fire(Event::new(new Payload()));
        $this->assertEmpty($subject->order);

        $this->event->fire($child);
        $this->assertEquals(['listener'], $subject->order);
    }

    /** @test */
    public function it_runs_ancestor_listeners_when_a_child_event_is_fired()
    {
        $subject = new class { public $order = []; };
        $descendant = new class(new Payload()) extends Event {};
        $listener = $this->makeListener('listener_fired', $subject);

        $this->event->trackEvents()
            ->subscribe(Event::class, $listener)
            ->fire($descendant);

        $this->assertEquals(['listener_fired'], $subject->order);

        $this->assertFalse($this->history->wasFiredByName(Event::class));
        $this->assertTrue($this->history->wasFiredByDescendant(Event::class));
    }

    protected function makeListener($name, $subject)
    {
        return new class($subject, $name) extends Listener {
            public $subject;

            public $name;

            public function __construct($subject, $name)
            {
                $this->subject = $subject;
                $this->name = $name;
            }

            public function react(Event $event)
            {
                $this->subject->order[] = $this->name;
            }
        };
    }
}
