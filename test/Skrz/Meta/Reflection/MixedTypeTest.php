<?php
namespace Skrz\Meta\Reflection;

class MixedTypeTest extends \PHPUnit_Framework_TestCase
{

	public function testFromStringWithMixed()
	{
		$this->assertSame(MixedType::getInstance(), MixedType::fromString("mixed"));
	}

	public function testFromStringWithScalar()
	{
		$this->assertSame(ScalarType::getInstance(), MixedType::fromString("scalar"));
	}

	public function testFromStringWithObject()
	{
		$this->assertSame(ObjectType::getInstance(), MixedType::fromString("object"));
	}

	public function testFromStringWithString()
	{
		$this->assertSame(StringType::getInstance(), MixedType::fromString("string"));
	}

	public function testFromStringWithInt()
	{
		$this->assertSame(IntType::getInstance(), MixedType::fromString("int"));
	}

	public function testFromStringWithFloat()
	{
		$this->assertSame(FloatType::getInstance(), MixedType::fromString("float"));
	}

	public function testFromStringWithResource()
	{
		$this->assertSame(ResourceType::getInstance(), MixedType::fromString("resource"));
	}

	public function testFromStringWithBool()
	{
		$this->assertSame(BoolType::getInstance(), MixedType::fromString("bool"));
	}

	public function testFromStringWithVoid()
	{
		$this->assertSame(VoidType::getInstance(), MixedType::fromString("void"));
		$this->assertSame(VoidType::getInstance(), MixedType::fromString("null"));
	}

	public function testFromStringWithMixedArray()
	{
		$arrayType = MixedType::fromString("array");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\ArrayType", $arrayType);
		$this->assertSame(MixedType::getInstance(), $arrayType->getBaseType());
		$this->assertSame($arrayType, MixedType::fromString("array"));
	}

	public function testFromStringWithStringArray()
	{
		$arrayType = MixedType::fromString("string[]");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\ArrayType", $arrayType);
		$this->assertSame(StringType::getInstance(), $arrayType->getBaseType());
		$this->assertSame($arrayType, MixedType::fromString("string[]"));
	}

	public function testFromStringWithStringArrayArray()
	{
		$arrayType = MixedType::fromString("string[][]");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\ArrayType", $arrayType);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\ArrayType", $arrayType->getBaseType());
		/** @var ArrayType $baseType */
		$baseType = $arrayType->getBaseType();
		$this->assertSame(StringType::getInstance(), $baseType->getBaseType());
		$this->assertSame($arrayType, MixedType::fromString("string[][]"));
	}

	public function testFromStringWithClassType()
	{
		$simpleClassType = MixedType::fromString("Skrz\\Meta\\Reflection\\Fixtures\\SimpleClass");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $simpleClassType);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleClass", $simpleClassType->getName());
	}

	public function testFromStringWithInterfaceType()
	{
		$simpleClassType = MixedType::fromString("Skrz\\Meta\\Reflection\\Fixtures\\SimpleInterface");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $simpleClassType);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleInterface", $simpleClassType->getName());
	}

	public function testFromStringWithTraitType()
	{
		$simpleClassType = MixedType::fromString("Skrz\\Meta\\Reflection\\Fixtures\\SimpleTrait");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $simpleClassType);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleTrait", $simpleClassType->getName());
	}

	public function testFromStringWithClassWithUnderscores()
	{
		$classType = MixedType::fromString("Skrz\\Meta\\Reflection\\Fixtures\\Class_With_Underscores");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $classType);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\Class_With_Underscores", $classType->getName());

		$instanceProperty = $classType->getProperty("instance");
	}

	public function testFromStringWithClassWithMethodWithParameters()
	{
		$classType = MixedType::fromString("Skrz\\Meta\\Reflection\\Fixtures\\ClassWithMethodWithParameters");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $classType);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\ClassWithMethodWithParameters", $classType->getName());

		$method = $classType->getMethod("method");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Method", $method);
		$parameters = $method->getParameters();
	}

}
