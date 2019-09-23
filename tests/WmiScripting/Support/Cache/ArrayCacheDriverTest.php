<?php

namespace Tests\WmiScripting\Support\Cache;

use Tests\TestCase;
use PhpWinTools\WmiScripting\Configuration\Config;
use PhpWinTools\WmiScripting\Support\Cache\ArrayDriver;
use PhpWinTools\WmiScripting\Support\Cache\CacheProvider;
use PhpWinTools\WmiScripting\Support\Cache\Events\CacheHit;
use PhpWinTools\WmiScripting\Support\Cache\Events\CacheMissed;
use PhpWinTools\WmiScripting\Support\Cache\Events\CacheCleared;
use PhpWinTools\WmiScripting\Support\Cache\Events\CacheKeyStored;
use PhpWinTools\WmiScripting\Support\Events\EventHistoryProvider;
use PhpWinTools\WmiScripting\Support\Cache\Events\CacheKeyDeleted;
use PhpWinTools\WmiScripting\Support\Cache\Events\CacheKeyExpired;
use PhpWinTools\WmiScripting\Exceptions\CacheInvalidArgumentException;

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
    public function it_fires_an_event_when_a_value_is_set_and_returns_true_when_successful()
    {
        $this->assertCount(4, $this->eventHistory->get(CacheKeyStored::class));
        $this->assertTrue($this->cache->set('test', $value = 'value'));
        $this->assertCount(5, $this->eventHistory->get(CacheKeyStored::class));
        $this->assertTrue($this->cache->has('test'));
        $this->assertSame($value, $this->cache->get('test'));
    }

    /** @test */
    public function it_throws_an_exception_when_setting_an_invalid_key()
    {
        $this->expectException(CacheInvalidArgumentException::class);

        $this->cache->set(new \stdClass(), 'test');
    }

    /** @test */
    public function it_returns_false_when_unable_to_store_multiple_items()
    {
        $this->assertFalse($this->cache->setMultiple([1 => 'value']));
    }

    /** @test */
    public function it_throws_an_exception_when_trying_to_get_an_invalid_key()
    {
        $this->expectException(CacheInvalidArgumentException::class);

        $this->cache->get(new \stdClass());
    }

    /** @test */
    public function it_sets_default_value_to_keys_that_do_not_exist_when_getting_multiple_and_fire_correct_events()
    {
        $results = $this->cache->get(['key2', 'not a key'], false);

        $this->assertSame($this->cached_values['key2'], $results['key2']);
        $this->assertFalse($results['not a key']);
        $this->assertCount(1, $hit = $this->eventHistory->get(CacheHit::class));
        $this->assertCount(1, $miss = $this->eventHistory->get(CacheMissed::class));
        $this->assertSame($results['key2'], $hit[0]->event()->payload()->get('value'));
        $this->assertSame('not a key', $miss[0]->event()->payload()->get('key'));
    }

    /** @test */
    public function it_can_return_a_cached_value_and_fire_event()
    {
        $this->assertSame($this->cached_values['key3'], $this->cache->get('key3'));
        $this->assertTrue($this->eventHistory->hasFired(CacheHit::class));
    }

    /** @test */
    public function it_can_delete_an_item_and_fire_an_event()
    {
        $this->assertSame($this->cached_values['key2'], $this->cache->get('key2'));
        $this->assertTrue($this->cache->delete('key2'));
        $this->assertFalse($this->cache->get('key2', false));
        $this->assertTrue($this->eventHistory->hasFired(CacheKeyDeleted::class));
    }

    /** @test */
    public function it_can_delete_multiple_items_fire_events()
    {
        $this->assertTrue($this->cache->notEmpty());
        $this->assertTrue($this->cache->delete(['key1', 'key2', 'key3', 'key4']));
        $this->assertCount(4, $this->eventHistory->get(CacheKeyDeleted::class));
        $this->assertTrue($this->cache->empty());
    }

    /** @test */
    public function it_returns_false_when_trying_delete_an_item_that_doesnt_exist_or_is_invalid()
    {
        $this->assertFalse($this->cache->delete('not a key'));
        $this->assertFalse($this->cache->driver()->deleteMultiple(['not a key']));
        $this->assertFalse($this->cache->delete([new \stdClass()]));
    }

    /** @test */
    public function it_can_get_multiple_values_and_fire_events()
    {
        $this->assertSame($this->cached_values, $this->cache->get(['key1', 'key2', 'key3', 'key4']));
        $this->assertCount(4, $this->eventHistory->get(CacheHit::class));
    }

    /** @test */
    public function it_can_clear_its_cache_and_fire_an_event()
    {
        $this->assertTrue($this->cache->notEmpty());
        $this->assertTrue($this->cache->clear());
        $this->assertTrue($this->cache->empty());
        $this->assertTrue($this->eventHistory->hasFired(CacheCleared::class));
    }

    /** @test */
    public function it_removes_expired_values_when_getting_multiple()
    {
        $this->cache->set('test', 'value', -30);
        $this->assertFalse($this->cache->get(['test'], false)['test']);
        $this->assertTrue($this->eventHistory->hasFired(CacheKeyExpired::class));
    }

    /** @test */
    public function it_removes_an_expired_key_and_fires_an_event()
    {
        $this->assertTrue($this->cache->set('test', 'value', -30));
        $this->assertFalse($this->cache->get('test', false));
        $this->assertTrue($this->eventHistory->hasFired(CacheKeyExpired::class));
    }
}
