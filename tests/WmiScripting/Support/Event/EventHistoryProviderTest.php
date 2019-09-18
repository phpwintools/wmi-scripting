<?php

namespace Tests\WmiScripting\Support\Event;

use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Support\Events\Event;
use PhpWinTools\WmiScripting\Support\Events\Context;
use PhpWinTools\WmiScripting\Support\Events\Listener;
use PhpWinTools\WmiScripting\Support\Bus\Events\CommandBusEvent;

class EventHistoryProviderTest extends EventProviderTest
{
    /** @test */
    public function it_does_not_track_events_by_default()
    {
        $this->event->fire(new Event(new Context()));

        $this->assertEmpty($this->history->all());
    }

    /** @test */
    public function it_can_track_events()
    {
        $notFired = new class(new Context()) extends Event {};
        $this->event->trackEvents()->fire(new Event(new Context()));

        $this->assertTrue($this->history->hasFired(Event::class));
        $this->assertTrue($this->history->hasNotFired(get_class($notFired)));
    }

    /** @test */
    public function it_can_count_events()
    {
        $this->event->trackEvents();
        $child = new class(new Context()) extends Event {};

        $this->event->fire($child);
        $this->event->fire(new Event(new Context()));
        $this->event->fire(new Event(new Context()));

//        dd($this->history->container());

        $this->assertEquals(3, $this->history->count());
        $this->assertEquals(3, $this->history->eventCount());
        $this->assertEquals(3, $this->history->eventCount(Event::class));
        $this->assertEquals(1, $this->history->eventCount(get_class($child)));
    }

    /** @test */
    public function it_can_count_events_through_ancestry()
    {
        $this->event->trackEvents();

        $child = new class(new Context()) extends Event {};

        $this->event->fire($child);
        $this->event->fire(Event::new(new Context()));

        $this->assertEquals(1, $this->history->eventCount(get_class($child)));
        $this->assertEquals(2, $this->history->eventCount(Event::class));
    }

    /** @test */
    public function it_can_count_events_through_ancestry_without_over_counting()
    {
        $this->event->trackEvents();

        $child = new class($bus = Config::instance()->commandBus(), 'test') extends CommandBusEvent {};
        $notFired = new class($bus = Config::instance()->commandBus(), 'nothing') extends CommandBusEvent {};

        $this->event->fire(Event::new(new Context()));
        $this->event->fire($child);
        $this->event->fire(new CommandBusEvent($bus, 'new test'));

        $this->assertEquals(3, $this->history->eventCount(Event::class));
        $this->assertEquals(2, $this->history->eventCount(CommandBusEvent::class));
        $this->assertEquals(1, $this->history->eventCount(get_class($child)));
        $this->assertEquals(0, $this->history->eventCount(get_class($notFired)));
    }

    /** @test */
    public function it_returns_events_in_the_order_that_they_were_fired()
    {
        $this->event->trackEvents();

        $child = new class($bus = Config::instance()->commandBus(), 'test') extends CommandBusEvent {};

        $this->event->fire($first = Event::new(new Context()));
        $this->event->fire($second = Event::new(new Context()));
        $this->event->fire($child);
        $this->event->fire($forth = new CommandBusEvent($bus, 'new test'));


        $this->assertSame($first, $this->history->all()[0]->event());
        $this->assertSame($second, $this->history->all()[1]->event());
        $this->assertSame($child, $this->history->all()[2]->event());
        $this->assertSame($forth, $this->history->all()[3]->event());
    }

    /** @test */
    public function it_can_return_all_fired_events_by_name()
    {
        $this->event->trackEvents();

        $child = new class($bus = Config::instance()->commandBus(), 'test') extends CommandBusEvent {};

        $this->event->fire($first = Event::new(new Context()));
        $this->event->fire($second = Event::new(new Context()));
        $this->event->fire($child);

        $this->assertCount(1, $firedEvents = $this->history->get(get_class($child)));
        $this->assertSame($child, $firedEvents[0]->event());

        $this->assertCount(3, $firedEvents = $this->history->get(Event::class));
        $this->assertSame($first, $firedEvents[0]->event());
        $this->assertSame($second, $firedEvents[1]->event());
        $this->assertSame($child, $firedEvents[2]->event());
    }

    /** @test */
    public function it_returns_an_empty_array_if_no_events_are_found_calls_default_value_if_callable_or_returns_it()
    {
        $this->assertEmpty($this->history->get(Event::class));
        $this->assertSame('nope', $this->history->get(Event::class, 'nope'));
        $this->assertSame('i returned', $this->history->get(Event::class, function () {
            return 'i returned';
        }));
    }

    /** @test */
    public function it_can_return_an_array_of_fired_events_by_the_listener_name_triggered()
    {
        $listener = $this->makeListener('listener', new class {
            public $order = [];
        });

        $this->event->trackEvents()
            ->subscribe(Event::class, $listener)
            ->fire($event = Event::new(new Context()));

        $this->assertSame($event, $this->history->getFromListener(get_class($listener))[0]->event());
    }

    /** @test */
    public function it_returns_an_empty_array_if_no_listeners_are_found_calls_default_value_if_callable_or_returns_it()
    {
        $this->assertEmpty($this->history->getFromListener(Listener::class));
        $this->assertSame('nope', $this->history->getFromListener(Listener::class, 'nope'));
        $this->assertSame('i returned', $this->history->getFromListener(Listener::class, function () {
            return 'i returned';
        }));
    }
}
