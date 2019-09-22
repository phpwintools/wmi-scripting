<?php

namespace Tests\WmiScripting\Support\Cache;

use Tests\TestCase;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Support\Cache\ArrayDriver;
use PhpWinTools\WmiScripting\Support\Cache\CacheProvider;
use PhpWinTools\WmiScripting\Support\Cache\Events\CacheHit;
use PhpWinTools\WmiScripting\Support\Cache\Events\CacheKeyStored;
use PhpWinTools\WmiScripting\Support\Events\EventHistoryProvider;

class ArrayCacheDriverTest extends TestCase
{
    /** @var CacheProvider */
    protected $cache;

    /** @var Config */
    protected $config;

    /** @var EventHistoryProvider  */
    protected $eventHistory;

    protected $cached_values = [
        'key1' => 'value1',
        'key2' => 'value2',
        'key3' => 'value3',
        'key4' => 'value4',
    ];

    protected $time_to_live_seconds = 60;

    protected function setUp()
    {
        $this->config = Config::newInstance()->trackEvents();
        $this->eventHistory = $this->config->eventHistoryProvider();
        $this->cache = new CacheProvider($this->config, new ArrayDriver($this->config));
        $this->cache->set($this->cached_values, $this->time_to_live_seconds);
    }

    /** @test */
    public function it_uses_the_array_driver_when_specified()
    {
        $this->assertInstanceOf(ArrayDriver::class, $this->cache->driver());
    }

    /** @test */
    public function it_fires_an_event_when_a_value_is_set()
    {
        $this->assertCount(4, $this->eventHistory->get(CacheKeyStored::class));
        $this->cache->set('test', $value = 'value');
        $this->assertCount(5, $this->eventHistory->get(CacheKeyStored::class));
        $this->assertSame($value, $this->cache->get('test'));
    }

    /** @test */
    public function it_can_return_a_cached_value_and_fire_event()
    {
        $this->assertSame($this->cached_values['key3'], $this->cache->get('key3'));
        $this->assertTrue($this->eventHistory->hasFired(CacheHit::class));
    }
}
