<?php

namespace Tests\Support;

use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Support\Events\Bus;
use Tests\TestCase;

class EventBusTest extends TestCase
{
    /** @test */
    public function it_instantiates_on_config_creation()
    {
        $this->assertInstanceOf(Bus::class, Config::instance()->bus());
    }

//    /** @test */
//    public function it_tracks_fired_events()
//    {
//
//    }
}
