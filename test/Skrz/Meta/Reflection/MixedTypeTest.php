<?php
namespace Skrz\Meta\Reflection;

use PHPUnit\Framework\TestCase;
use Skrz\Meta\Reflection\Fixtures\Class_With_Underscores;
use Skrz\Meta\Reflection\Fixtures\ClassWithMethodWithParameters;
use Skrz\Meta\Reflection\Fixtures\SimpleClass;
use Skrz\Meta\Reflection\Fixtures\SimpleInterface;
use Skrz\Meta\Reflection\Fixtures\SimpleTrait;

class MixedTypeTest extends TestCase
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
		$this->assertInstanceOf(ArrayType::class, $arrayType);
		$this->assertSame(MixedType::getInstance(), $arrayType->getBaseType());
		$this->assertSame($arrayType, MixedType::fromString("array"));
	}

	public function testFromStringWithStringArray()
	{
		$arrayType = MixedType::fromString("string[]");
		$this->assertInstanceOf(ArrayType::class, $arrayType);
		$this->assertSame(StringType::getInstance(), $arrayType->getBaseType());
		$this->assertSame($arrayType, MixedType::fromString("string[]"));
	}

	public function testFromStringWithStringArrayArray()
	{
		$arrayType = MixedType::fromString("string[][]");
		$this->assertInstanceOf(ArrayType::class, $arrayType);
		$this->assertInstanceOf(ArrayType::class, $arrayType->getBaseType());
		/** @var ArrayType $baseType */
		$baseType = $arrayType->getBaseType();
		$this->assertSame(StringType::getInstance(), $baseType->getBaseType());
		$this->assertSame($arrayType, MixedType::fromString("string[][]"));
	}

	public function testFromStringWithClassType()
	{
		$simpleClassType = MixedType::fromString(SimpleClass::class);
		$this->assertInstanceOf(Type::class, $simpleClassType);
		$this->assertEquals(SimpleClass::class, $simpleClassType->getName());
	}

	public function testFromStringWithInterfaceType()
	{
		$simpleClassType = MixedType::fromString(SimpleInterface::class);
		$this->assertInstanceOf(Type::class, $simpleClassType);
		$this->assertEquals(SimpleInterface::class, $simpleClassType->getName());
	}

	public function testFromStringWithTraitType()
	{
		$simpleClassType = MixedType::fromString(SimpleTrait::class);
		$this->assertInstanceOf(Type::class, $simpleClassType);
		$this->assertEquals(SimpleTrait::class, $simpleClassType->getName());
	}

	public function testFromStringWithClassWithUnderscores()
	{
		$classType = MixedType::fromString(Class_With_Underscores::class);
		$this->assertInstanceOf(Type::class, $classType);
		$this->assertEquals(Class_With_Underscores::class, $classType->getName());

		$instanceProperty = $classType->getProperty("instance");
	}

	public function testFromStringWithClassWithMethodWithParameters()
	{
		$classType = MixedType::fromString(ClassWithMethodWithParameters::class);
		$this->assertInstanceOf(Type::class, $classType);
		$this->assertEquals(ClassWithMethodWithParameters::class, $classType->getName());

		$method = $classType->getMethod("method");
		$this->assertInstanceOf(Method::class, $method);
		$parameters = $method->getParameters();
	}

}
