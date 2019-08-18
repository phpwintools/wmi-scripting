<?php

namespace Tests\Unit;

use PhpWinTools\WmiScripting\Models\LogicalDisk;
use PhpWinTools\WmiScripting\Win32Model;
use Tests\TestCase;

class Win32ModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Win32Model::class, new Win32Model());
        $this->assertInstanceOf(Win32Model::class, Win32Model::newInstance());
    }

    /** @test */
    public function it_can_return_an_attribute_that_has_corresponding_property()
    {
        $model = new LogicalDisk(['driveType' => 1234]);

        $this->assertTrue($model->hasProperty('driveType'));
        $this->assertSame($model->getAttribute('driveType'), 1234);
    }

    /** @test */
    public function it_can_modify_a_property_with_an_attribute_method()
    {
        $class = new class extends Win32Model {
            protected $property = 'this_string_has_spaces';

            public function getPropertyAttribute($value)
            {
                return str_replace('_', ' ', $value);
            }
        };

        $this->assertSame('this string has spaces', $class->getAttribute('property'));
    }

    /** @test */
    public function it_can_modify_an_unmapped_attribute_with_an_attribute_method()
    {
        $class = new class extends Win32Model {
            public function getUnmappedAttributeAttribute($value)
            {
                return str_replace('SHOULD BE', 'AM', strtoupper($value));
            }
        };

        $class->setUnmappedAttribute('unmapped_attribute', 'i should be uppercase');
        $this->assertSame($class->getAttribute('unmapped_attribute'), 'I AM UPPERCASE');

        /** @var Win32Model $class */
        $class = new $class();

        $class->setUnmappedAttribute('unmappedAttribute', 'i should be uppercase');
        $this->assertSame($class->getAttribute('unmappedAttribute'), 'I AM UPPERCASE');
    }

    /** @test */
    public function it_can_have_a_calculated_value_using_an_attribute_method()
    {
        $class = new class extends Win32Model {
            public function getCalculatedAttribute()
            {
                return "i'm not a property or attribute";
            }
        };

        $this->assertSame("i'm not a property or attribute", $class->getAttribute('calculated'));
    }

    /** @test */
    public function it_returns_null_if_no_property_or_attribute_is_found()
    {
        $class = new class extends Win32Model{};

        $this->assertNull($class->getAttribute('i do not exist'));
    }

    /** @test */
    public function it_returns_default_if_no_property_or_attribute_is_found()
    {
        $class = new class extends Win32Model{};

        $this->assertSame('but this works', $class->getAttribute('i do not exist', 'but this works'));
    }

    /** @test */
    public function it_can_return_an_unmapped_attribute_when_the_property_doesnt_exist()
    {
        $class = new class extends Win32Model{};

        $class->setUnmappedAttribute('test_key', 'test_value');

        $this->assertSame($class->getAttribute('test_key'), 'test_value');
    }

    /** @test */
    public function it_can_get_an_attribute_by_its_replaced_name()
    {
        $class = new class extends Win32Model {
            public $oldName = 'i am some text';

            protected $attribute_name_replacements = ['oldName' => 'newName'];
        };

        $this->assertSame($class->oldName, $class->getAttribute('newName'));
    }

    /** @test */
    public function it_can_return_an_array()
    {
        $class = new class extends Win32Model {
            protected $wmi_class_name = 'test';
        };

        $this->assertIsArray($class->toArray());
    }

    /** @test */
    public function it_transforms_names_when_transforming_to_an_array()
    {
        $class = new class extends Win32Model {
            public $oldName = 'i am some text';

            protected $wmi_class_name = 'test';

            protected $attribute_name_replacements = ['oldName' => 'newName'];
        };

        $this->assertArrayNotHasKey('oldName', $class->toArray());
        $this->assertArrayHasKey('newName', $class->toArray());
        $this->assertSame($class->oldName, $class->toArray()['newName']);
    }

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
