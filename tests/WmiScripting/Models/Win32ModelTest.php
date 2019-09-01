<?php

namespace Tests\WmiScripting\Models;

use Tests\TestCase;
use PhpWinTools\WmiScripting\Scripting;
use PhpWinTools\WmiScripting\Connection;
use PhpWinTools\WmiScripting\Query\Builder;
use PhpWinTools\WmiScripting\Models\Win32Model;
use PhpWinTools\WmiScripting\Models\LogicalDisk;
use PhpWinTools\WmiScripting\MappingStrings\Mappings;
use PhpWinTools\WmiScripting\Collections\ModelCollection;
use PhpWinTools\WmiScripting\Exceptions\InvalidArgumentException;
use PhpWinTools\WmiScripting\Exceptions\WmiClassNotFoundException;

class Win32ModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Win32Model::class, new Win32Model());
        $this->assertInstanceOf(Win32Model::class, Win32Model::newInstance());
    }

    /** @test */
    public function it_returns_an_instance_of_builder()
    {
        Scripting::fake($this)->win32Model(LogicalDisk::class);

        $testModel = new class extends Win32Model {
            protected $wmi_class_name = 'test';
        };

        $this->assertInstanceOf(Builder::class, $testModel::query());
    }

    /** @test */
    public function it_can_detect_the_wmi_class_if_not_provided()
    {
        $this->assertSame('Win32_LogicalDisk', LogicalDisk::newInstance()->getAttribute('wmi_class_name'));
    }

    /** @test */
    public function it_can_detect_the_wmi_class_from_its_parent()
    {
        $wmiClass = new class extends LogicalDisk {};

        $this->assertSame('Win32_LogicalDisk', $wmiClass->getAttribute('wmi_class_name'));
    }

    /** @test */
    public function it_will_throw_an_exception_if_the_wmi_class_cannot_be_detected()
    {
        $this->expectException(WmiClassNotFoundException::class);

        (new class extends Win32Model {})->getAttribute('wmi_class_name');
    }

    /** @test */
    public function it_calls_all_on_an_instance_of_builder_with_the_given_connection()
    {
         $wmiClass = new class extends Win32Model {
            public static $builder;

            protected $wmi_class_name = 'test';

            public static function query($connection = null)
            {
                return static::$builder;
            }
        };

        $connection = $this->getMockBuilder(Connection::class)->getMock();
        $connection->expects($this->once())->method('connect');

        $builder = $this->getMockBuilder(Builder::class)->setConstructorArgs([$wmiClass, $connection])->getMock();
        $builder->expects($this->once())->method('all')->willReturn(new ModelCollection());

        $wmiClass::$builder = $builder;
        $wmiClass::all($connection);
    }

    /** @test */
    public function it_returns_the_class_name_without_the_namespace()
    {
        $this->assertSame('LogicalDisk', LogicalDisk::newInstance()->getClassName());
    }

    /** @test */
    public function it_has_a_default_connection_of_default()
    {
        $this->assertSame('default', LogicalDisk::newInstance()->getConnectionName());
        $this->assertInstanceOf(Connection::class, LogicalDisk::newInstance()->getConnection());
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
    public function it_can_return_to_a_json_string()
    {
        $class = new class extends Win32Model {
            protected $wmi_class_name = 'test';
        };

        $this->assertJson($class->toJson());
    }

    /** @test */
    public function it_returns_a_json_string_when_cast_to_string()
    {
        $class = new class extends Win32Model {
            protected $wmi_class_name = 'test';
        };

        $this->assertJson($class->toString());
        $this->assertJson((string) $class);
    }

    /** @test */
    public function it_reduces_value_arrays_to_just_its_value()
    {
        $test_array['valueArray'] = ['value' => 'test value'];
        $class = new class($test_array) extends Win32Model {
            protected $wmi_class_name = 'test';
        };

        $this->assertSame($test_array['valueArray']['value'], $class->toArray()['valueArray']);
    }

    /** @test */
    public function it_can_map_a_constant_to_a_string()
    {
        $constants = new class extends Mappings {
            const TEST = 1;

            const STRING_ARRAY = [
                self::TEST => 'test string',
            ];
        };

        $wmiClass = new class extends Win32Model {
            protected $wmi_class_name = 'test';

            public $constant_class;

            protected $testConstant = 1;

            public function getTestConstantAttribute($value)
            {
                return $this->mapConstant($this->constant_class, $value);
            }
        };

        $wmiClass->constant_class = get_class($constants);

        $this->assertSame($constants::string(1), $wmiClass->getAttribute('testConstant'));
    }

    /** @test */
    public function it_can_returns_the_constant_in_brackets_with_unknown_if_it_cant_be_mapped()
    {
        $constants = new class extends Mappings {
            const TEST = 1;

            const STRING_ARRAY = [
                self::TEST => 'test string',
            ];
        };

        $wmiClass = new class extends Win32Model {
            protected $wmi_class_name = 'test';

            public $constant_class;

            protected $testConstant = 2;

            public function getTestConstantAttribute($value)
            {
                return $this->mapConstant($this->constant_class, $value);
            }
        };

        $wmiClass->constant_class = get_class($constants);

        $this->assertSame('[2] - UNKNOWN', $wmiClass->getAttribute('testConstant'));
    }

    /** @test */
    public function it_throws_exception_if_constant_class_doesnt_extend_mappings()
    {
        $class = new class {};

        $wmiClass = new class extends Win32Model {
            protected $wmi_class_name = 'test';

            public $constant_class;

            protected $testConstant = 1234;

            public function getTestConstantAttribute($value)
            {
                return $this->mapConstant($this->constant_class, $value);
            }
        };

        $wmiClass->constant_class = get_class($class);

        $this->expectException(InvalidArgumentException::class);

        $wmiClass->getAttribute('testConstant');
    }
}
