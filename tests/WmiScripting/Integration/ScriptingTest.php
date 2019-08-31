<?php

namespace Tests\WmiScripting\Integration;

use Tests\TestCase;
use PhpWinTools\WmiScripting\Scripting;
use PhpWinTools\WmiScripting\Models\LogicalDisk;
use PhpWinTools\WmiScripting\Collections\ModelCollection;

class ScriptingTest extends TestCase
{
    /** @var Scripting */
    protected $scripting;

    protected function setUp()
    {
        parent::setUp();

        $this->scripting = new Scripting();
    }

    /**
     * @group uses_real_com
     *
     * @test
     */
    public function it_can_actually_return_a_logical_disk()
    {
        $this->assertInstanceOf(ModelCollection::class, $disks = $this->scripting->query()->logicalDisk()->get());
        $this->assertInstanceOf(LogicalDisk::class, $disks->first(function (LogicalDisk $disk) {
            return $disk->getAttribute('deviceID') === 'C:';
        }));
    }
}
