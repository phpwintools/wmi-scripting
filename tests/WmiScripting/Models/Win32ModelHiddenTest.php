<?php

namespace Tests\WmiScripting\Models;

use PhpWinTools\WmiScripting\Models\Account;
use Tests\TestCase;
use PhpWinTools\WmiScripting\Models\Win32Model;
use function PhpWinTools\WmiScripting\Support\class_traits;

class Win32ModelHiddenTest extends TestCase
{
    /** @test */
    public function it_can_hide_values_from_array_transform()
    {
        $class = new class extends Win32Model {
            public $iAmHidden = 'i am some text';

            protected $wmi_class_name = 'test';

            protected $hidden_attributes = ['iAmHidden'];
        };

        $this->assertArrayNotHasKey('iAmHidden', $class->toArray());
    }
}
