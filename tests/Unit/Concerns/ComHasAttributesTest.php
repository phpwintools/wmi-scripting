<?php

namespace Tests\Unit\Concerns;

use Tests\TestCase;
use Tests\Mocks\ClassHasAttributes;

class ComHasAttributesTest extends TestCase
{
    /** @var ClassHasAttributes */
    protected $class;

    protected function setUp()
    {
        parent::setUp();

        $this->class = new ClassHasAttributes();
    }

    /** @test */
    public function a_class_can_instantiate_with_this_trait_active()
    {
        $this->assertInstanceOf(ClassHasAttributes::class, $this->class);
    }

    /** @test */
    public function it_can_get_a_basic_attribute()
    {
        $this->class->testProperty = 'test';

        $this->assertSame($this->class->getAttribute('testProperty'), 'test');
    }

    /** @test */
    public function it_can_modify_a_property_with_an_attribute_method()
    {
        $this->class->testProperty1 = 'this_string_has_spaces';

        $this->assertSame('this string has spaces', $this->class->getAttribute('testProperty1'));
    }

    /** @test */
    public function it_can_have_a_calculated_value_using_an_attribute_method()
    {
        $this->class->testProperty1 = 20;

        $this->assertSame(25, $this->class->getAttribute('calculatedTestValue'));
    }

    /** @test */
    public function it_returns_null_if_no_attribute_is_found()
    {
        $this->assertNull($this->class->getAttribute('i am not an attribute nor property'));
    }

    /** @test */
    public function it_can_return_an_unmapped_attribute_when_the_property_doesnt_exist()
    {
        $this->class->setUnmappedAttribute('test_key', 'test_value');

        $this->assertSame($this->class->getAttribute('test_key'), 'test_value');
    }

    /** @test */
    public function it_an_modify_a_hidden_attribute_with_an_attribute_method()
    {
        $this->class->setUnmappedAttribute('unmapped_attribute_modified', 'i should be uppercase');
        $this->assertSame($this->class->getAttribute('unmapped_attribute_modified'), 'I AM UPPERCASE');

        $this->class->setUnmappedAttribute('unmappedAttributeModified', 'i should be uppercase');
        $this->assertSame($this->class->getAttribute('unmappedAttributeModified'), 'I AM UPPERCASE');
    }
}
