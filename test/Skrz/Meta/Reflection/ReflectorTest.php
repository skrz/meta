<?php
namespace Skrz\Meta\Reflection;

use Skrz\Meta\Reflection\Fixtures\ClassWithMethodWithParameters;
use Skrz\Meta\Reflection\Fixtures\ClassWithProperties;
use Skrz\Meta\Reflection\Fixtures\ClassWithReturningMethods;
use Skrz\Meta\Reflection\Fixtures\ClassWithUseStatements;
use Skrz\Meta\Reflection\Fixtures\SimpleClass;
use Skrz\Meta\Reflection\Fixtures\SimpleInterface;
use Skrz\Meta\Reflection\Fixtures\SimpleTrait;

class ReflectorTest extends \PHPUnit_Framework_TestCase
{

	/** @var Reflector */
	private $reflector;

	public function setUp()
	{
		$this->reflector = new Reflector();
	}

	public function testReflectSimpleClass()
	{
		/** @var Type $type */
		$type = $this->reflector->reflect("Skrz\\Meta\\Reflection\\Fixtures\\SimpleClass");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $type);

		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleClass", $type->getName());
		$this->assertFalse($type->isInterface());
		$this->assertFalse($type->isTrait());
	}

	public function testReflectSimpleInterface()
	{
		/** @var Type $type */
		$type = $this->reflector->reflect("Skrz\\Meta\\Reflection\\Fixtures\\SimpleInterface");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $type);

		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleInterface", $type->getName());
		$this->assertTrue($type->isInterface());
		$this->assertFalse($type->isTrait());
	}

	public function testReflectSimpleTrait()
	{
		/** @var Type $type */
		$type = $this->reflector->reflect("Skrz\\Meta\\Reflection\\Fixtures\\SimpleTrait");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $type);

		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleTrait", $type->getName());
		$this->assertFalse($type->isInterface());
		$this->assertTrue($type->isTrait());
	}

	public function testReflectSameClassMultipleTimes()
	{
		// 1st time
		$type = $this->reflector->reflect("Skrz\\Meta\\Reflection\\Fixtures\\SimpleClass");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $type);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleClass", $type->getName());

		// 2nd time
		$type = $this->reflector->reflect("Skrz\\Meta\\Reflection\\Fixtures\\SimpleClass");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $type);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleClass", $type->getName());

		// 3rd time (just to be sure)
		$type = $this->reflector->reflect("Skrz\\Meta\\Reflection\\Fixtures\\SimpleClass");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $type);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleClass", $type->getName());
	}

	public function testReflectUseStatements()
	{
		$type = $this->reflector->reflect("Skrz\\Meta\\Reflection\\Fixtures\\ClassWithUseStatements");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $type);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\ClassWithUseStatements", $type->getName());

		$this->assertNotEmpty($type->getUseStatements());
		$this->assertArrayHasKey("classwithusestatements", $type->getUseStatements());
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\ClassWithUseStatements", $type->getUseStatements()["classwithusestatements"]);
		$this->assertArrayHasKey("bar", $type->getUseStatements());
		$this->assertEquals("Foo\\Bar", $type->getUseStatements()["bar"]);
		$this->assertArrayHasKey("foo", $type->getUseStatements());
		$this->assertEquals("Bar\\Foo", $type->getUseStatements()["foo"]);
		$this->assertArrayHasKey("barbaz", $type->getUseStatements());
		$this->assertEquals("Bar\\Baz", $type->getUseStatements()["barbaz"]);
	}

	public function testReflectProperties()
	{
		$type = $this->reflector->reflect("Skrz\\Meta\\Reflection\\Fixtures\\ClassWithProperties");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $type);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\ClassWithProperties", $type->getName());

		$property = $type->getProperty("privateProperty");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Property", $property);
		$this->assertEquals("privateProperty", $property->getName());
		$this->assertTrue($property->isPrivate());

		$property = $type->getProperty("protectedProperty");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Property", $property);
		$this->assertEquals("protectedProperty", $property->getName());
		$this->assertTrue($property->isProtected());

		$property = $type->getProperty("publicProperty");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Property", $property);
		$this->assertEquals("publicProperty", $property->getName());
		$this->assertTrue($property->isPublic());

		$property = $type->getProperty("mixedProperty");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Property", $property);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\MixedType", $property->getType());

		$property = $type->getProperty("intProperty");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Property", $property);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\IntType", $property->getType());

		$property = $type->getProperty("floatProperty");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Property", $property);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\FloatType", $property->getType());

		$property = $type->getProperty("boolProperty");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Property", $property);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\BoolType", $property->getType());

		$property = $type->getProperty("resourceProperty");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Property", $property);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\ResourceType", $property->getType());

		$property = $type->getProperty("callableProperty");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Property", $property);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\CallableType", $property->getType());

		$property = $type->getProperty("stringProperty");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Property", $property);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\StringType", $property->getType());

		$property = $type->getProperty("classProperty");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Property", $property);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $property->getType());
		/** @var Type $propertyType */
		$propertyType = $property->getType();
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleClass", $propertyType->getName());
	}

	public function testReflectMethods()
	{
		$type = $this->reflector->reflect("Skrz\\Meta\\Reflection\\Fixtures\\ClassWithReturningMethods");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $type);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\ClassWithReturningMethods", $type->getName());

		$method = $type->getMethod("returnNothing");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Method", $method);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\VoidType", $method->getType());

		$method = $type->getMethod("returnMixed");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Method", $method);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\MixedType", $method->getType());

		$method = $type->getMethod("returnString");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Method", $method);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\StringType", $method->getType());

		$method = $type->getMethod("returnIntArray");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Method", $method);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\ArrayType", $method->getType());
		/** @var ArrayType $arrayType */
		$arrayType = $method->getType();
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\IntType", $arrayType->getBaseType());

		$method = $type->getMethod("returnVoid");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Method", $method);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\VoidType", $method->getType());

		$method = $type->getMethod("returnClass");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Method", $method);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $method->getType());
		/** @var Type $returnType */
		$returnType = $method->getType();
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleClass", $returnType->getName());
	}

	public function testReflectParameters()
	{
		$type = $this->reflector->reflect("Skrz\\Meta\\Reflection\\Fixtures\\ClassWithMethodWithParameters");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $type);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\ClassWithMethodWithParameters", $type->getName());

		$method = $type->getMethod("mixedMethod");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Method", $method);

		$parameter = $method->getParameter(0);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Parameter", $parameter);
		$this->assertEquals("mixed", $parameter->getName());
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\MixedType", $parameter->getType());

		$parameter = $method->getParameter(1);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Parameter", $parameter);
		$this->assertEquals("anotherMixed", $parameter->getName());
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\MixedType", $parameter->getType());

		$method = $type->getMethod("method");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Method", $method);

		$parameter = $method->getParameter("int");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Parameter", $parameter);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\IntType", $parameter->getType());

		$parameter = $method->getParameter("string");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Parameter", $parameter);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\StringType", $parameter->getType());

		$parameter = $method->getParameter("array");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Parameter", $parameter);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\ArrayType", $parameter->getType());
		/** @var ArrayType $arrayType */
		$arrayType = $parameter->getType();
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\MixedType", $arrayType->getBaseType());

		$parameter = $method->getParameter("interface");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Parameter", $parameter);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $parameter->getType());
		/** @var Type $interfaceType */
		$interfaceType = $parameter->getType();
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleInterface", $interfaceType->getName());

		$parameter = $method->getParameter("mixed");
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Parameter", $parameter);
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\MixedType", $parameter->getType());
	}

	public function testReflectFileSimpleClass()
	{
		$types = $this->reflector->reflectFile(__DIR__ . "/Fixtures/SimpleClass.php");
		$this->assertCount(1, $types);

		$type = $types[0];
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $type);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleClass", $type->getName());
		$this->assertFalse($type->isInterface());
		$this->assertFalse($type->isTrait());
	}

	public function testReflectFileSimpleInterface()
	{
		$types = $this->reflector->reflectFile(__DIR__ . "/Fixtures/SimpleInterface.php");
		$this->assertCount(1, $types);

		$type = $types[0];
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $type);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleInterface", $type->getName());
		$this->assertTrue($type->isInterface());
		$this->assertFalse($type->isTrait());
	}

	public function testReflectFileSimpleTrait()
	{
		$types = $this->reflector->reflectFile(__DIR__ . "/Fixtures/SimpleTrait.php");
		$this->assertCount(1, $types);

		$type = $types[0];

		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $type);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleTrait", $type->getName());
		$this->assertFalse($type->isInterface());
		$this->assertTrue($type->isTrait());
	}

	public function testReflectFileSameFileMultipleTimes()
	{
		// 1st time
		$types = $this->reflector->reflectFile(__DIR__ . "/Fixtures/SimpleClass.php");
		$this->assertCount(1, $types);
		$type = $types[0];
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $type);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleClass", $type->getName());

		// 2nd time
		$types = $this->reflector->reflectFile(__DIR__ . "/Fixtures/SimpleClass.php");
		$this->assertCount(1, $types);
		$type = $types[0];
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $type);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleClass", $type->getName());

		// 3rd time (just to be sure)
		$types = $this->reflector->reflectFile(__DIR__ . "/Fixtures/SimpleClass.php");
		$this->assertCount(1, $types);
		$type = $types[0];
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $type);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleClass", $type->getName());
	}

	public function testReflectFileMultipleFiles()
	{
		$types = $this->reflector->reflectFile(__DIR__ . "/Fixtures/SimpleClass.php");
		$this->assertCount(1, $types);
		$type = $types[0];
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $type);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleClass", $type->getName());

		$types = $this->reflector->reflectFile(__DIR__ . "/Fixtures/SimpleInterface.php");
		$this->assertCount(1, $types);
		$type = $types[0];
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $type);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleInterface", $type->getName());

		$types = $this->reflector->reflectFile(__DIR__ . "/Fixtures/SimpleTrait.php");
		$this->assertCount(1, $types);
		$type = $types[0];
		$this->assertInstanceOf("Skrz\\Meta\\Reflection\\Type", $type);
		$this->assertEquals("Skrz\\Meta\\Reflection\\Fixtures\\SimpleTrait", $type->getName());
	}

}
