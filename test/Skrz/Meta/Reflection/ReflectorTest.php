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
		$type = $this->reflector->reflect(SimpleClass::class);
		$this->assertInstanceOf(Type::class, $type);

		$this->assertEquals(SimpleClass::class, $type->getName());
		$this->assertFalse($type->isInterface());
		$this->assertFalse($type->isTrait());
	}

	public function testReflectSimpleInterface()
	{
		/** @var Type $type */
		$type = $this->reflector->reflect(SimpleInterface::class);
		$this->assertInstanceOf(Type::class, $type);

		$this->assertEquals(SimpleInterface::class, $type->getName());
		$this->assertTrue($type->isInterface());
		$this->assertFalse($type->isTrait());
	}

	public function testReflectSimpleTrait()
	{
		/** @var Type $type */
		$type = $this->reflector->reflect(SimpleTrait::class);
		$this->assertInstanceOf(Type::class, $type);

		$this->assertEquals(SimpleTrait::class, $type->getName());
		$this->assertFalse($type->isInterface());
		$this->assertTrue($type->isTrait());
	}

	public function testReflectSameClassMultipleTimes()
	{
		// 1st time
		$type = $this->reflector->reflect(SimpleClass::class);
		$this->assertInstanceOf(Type::class, $type);
		$this->assertEquals(SimpleClass::class, $type->getName());

		// 2nd time
		$type = $this->reflector->reflect(SimpleClass::class);
		$this->assertInstanceOf(Type::class, $type);
		$this->assertEquals(SimpleClass::class, $type->getName());

		// 3rd time (just to be sure)
		$type = $this->reflector->reflect(SimpleClass::class);
		$this->assertInstanceOf(Type::class, $type);
		$this->assertEquals(SimpleClass::class, $type->getName());
	}

	public function testReflectUseStatements()
	{
		$type = $this->reflector->reflect(ClassWithUseStatements::class);
		$this->assertInstanceOf(Type::class, $type);
		$this->assertEquals(ClassWithUseStatements::class, $type->getName());

		$this->assertNotEmpty($type->getUseStatements());
		$this->assertArrayHasKey("classwithusestatements", $type->getUseStatements());
		$this->assertEquals(ClassWithUseStatements::class, $type->getUseStatements()["classwithusestatements"]);
		$this->assertArrayHasKey("bar", $type->getUseStatements());
		$this->assertEquals("Foo\\Bar", $type->getUseStatements()["bar"]);
		$this->assertArrayHasKey("foo", $type->getUseStatements());
		$this->assertEquals("Bar\\Foo", $type->getUseStatements()["foo"]);
		$this->assertArrayHasKey("barbaz", $type->getUseStatements());
		$this->assertEquals("Bar\\Baz", $type->getUseStatements()["barbaz"]);
	}

	public function testReflectProperties()
	{
		$type = $this->reflector->reflect(ClassWithProperties::class);
		$this->assertInstanceOf(Type::class, $type);
		$this->assertEquals(ClassWithProperties::class, $type->getName());

		$property = $type->getProperty("privateProperty");
		$this->assertInstanceOf(Property::class, $property);
		$this->assertEquals("privateProperty", $property->getName());
		$this->assertTrue($property->isPrivate());

		$property = $type->getProperty("protectedProperty");
		$this->assertInstanceOf(Property::class, $property);
		$this->assertEquals("protectedProperty", $property->getName());
		$this->assertTrue($property->isProtected());

		$property = $type->getProperty("publicProperty");
		$this->assertInstanceOf(Property::class, $property);
		$this->assertEquals("publicProperty", $property->getName());
		$this->assertTrue($property->isPublic());

		$property = $type->getProperty("mixedProperty");
		$this->assertInstanceOf(Property::class, $property);
		$this->assertInstanceOf(MixedType::class, $property->getType());

		$property = $type->getProperty("intProperty");
		$this->assertInstanceOf(Property::class, $property);
		$this->assertInstanceOf(IntType::class, $property->getType());

		$property = $type->getProperty("floatProperty");
		$this->assertInstanceOf(Property::class, $property);
		$this->assertInstanceOf(FloatType::class, $property->getType());

		$property = $type->getProperty("boolProperty");
		$this->assertInstanceOf(Property::class, $property);
		$this->assertInstanceOf(BoolType::class, $property->getType());

		$property = $type->getProperty("resourceProperty");
		$this->assertInstanceOf(Property::class, $property);
		$this->assertInstanceOf(ResourceType::class, $property->getType());

		$property = $type->getProperty("callableProperty");
		$this->assertInstanceOf(Property::class, $property);
		$this->assertInstanceOf(CallableType::class, $property->getType());

		$property = $type->getProperty("stringProperty");
		$this->assertInstanceOf(Property::class, $property);
		$this->assertInstanceOf(StringType::class, $property->getType());

		$property = $type->getProperty("classProperty");
		$this->assertInstanceOf(Property::class, $property);
		$this->assertInstanceOf(Type::class, $property->getType());
		/** @var Type $propertyType */
		$propertyType = $property->getType();
		$this->assertEquals(SimpleClass::class, $propertyType->getName());
	}

	public function testReflectMethods()
	{
		$type = $this->reflector->reflect(ClassWithReturningMethods::class);
		$this->assertInstanceOf(Type::class, $type);
		$this->assertEquals(ClassWithReturningMethods::class, $type->getName());

		$method = $type->getMethod("returnNothing");
		$this->assertInstanceOf(Method::class, $method);
		$this->assertInstanceOf(VoidType::class, $method->getType());

		$method = $type->getMethod("returnMixed");
		$this->assertInstanceOf(Method::class, $method);
		$this->assertInstanceOf(MixedType::class, $method->getType());

		$method = $type->getMethod("returnString");
		$this->assertInstanceOf(Method::class, $method);
		$this->assertInstanceOf(StringType::class, $method->getType());

		$method = $type->getMethod("returnIntArray");
		$this->assertInstanceOf(Method::class, $method);
		$this->assertInstanceOf(ArrayType::class, $method->getType());
		/** @var ArrayType $arrayType */
		$arrayType = $method->getType();
		$this->assertInstanceOf(IntType::class, $arrayType->getBaseType());

		$method = $type->getMethod("returnVoid");
		$this->assertInstanceOf(Method::class, $method);
		$this->assertInstanceOf(VoidType::class, $method->getType());

		$method = $type->getMethod("returnClass");
		$this->assertInstanceOf(Method::class, $method);
		$this->assertInstanceOf(Type::class, $method->getType());
		/** @var Type $returnType */
		$returnType = $method->getType();
		$this->assertEquals(SimpleClass::class, $returnType->getName());
	}

	public function testReflectParameters()
	{
		$type = $this->reflector->reflect(ClassWithMethodWithParameters::class);
		$this->assertInstanceOf(Type::class, $type);
		$this->assertEquals(ClassWithMethodWithParameters::class, $type->getName());

		$method = $type->getMethod("mixedMethod");
		$this->assertInstanceOf(Method::class, $method);

		$parameter = $method->getParameter(0);
		$this->assertInstanceOf(Parameter::class, $parameter);
		$this->assertEquals("mixed", $parameter->getName());
		$this->assertInstanceOf(MixedType::class, $parameter->getType());

		$parameter = $method->getParameter(1);
		$this->assertInstanceOf(Parameter::class, $parameter);
		$this->assertEquals("anotherMixed", $parameter->getName());
		$this->assertInstanceOf(MixedType::class, $parameter->getType());

		$method = $type->getMethod("method");
		$this->assertInstanceOf(Method::class, $method);

		$parameter = $method->getParameter("int");
		$this->assertInstanceOf(Parameter::class, $parameter);
		$this->assertInstanceOf(IntType::class, $parameter->getType());

		$parameter = $method->getParameter("string");
		$this->assertInstanceOf(Parameter::class, $parameter);
		$this->assertInstanceOf(StringType::class, $parameter->getType());

		$parameter = $method->getParameter("array");
		$this->assertInstanceOf(Parameter::class, $parameter);
		$this->assertInstanceOf(ArrayType::class, $parameter->getType());
		/** @var ArrayType $arrayType */
		$arrayType = $parameter->getType();
		$this->assertInstanceOf(MixedType::class, $arrayType->getBaseType());

		$parameter = $method->getParameter("interface");
		$this->assertInstanceOf(Parameter::class, $parameter);
		$this->assertInstanceOf(Type::class, $parameter->getType());
		/** @var Type $interfaceType */
		$interfaceType = $parameter->getType();
		$this->assertEquals(SimpleInterface::class, $interfaceType->getName());

		$parameter = $method->getParameter("mixed");
		$this->assertInstanceOf(Parameter::class, $parameter);
		$this->assertInstanceOf(MixedType::class, $parameter->getType());
	}

	public function testReflectFileSimpleClass()
	{
		$types = $this->reflector->reflectFile(__DIR__ . "/Fixtures/SimpleClass.php");
		$this->assertCount(1, $types);

		$type = $types[0];
		$this->assertInstanceOf(Type::class, $type);
		$this->assertEquals(SimpleClass::class, $type->getName());
		$this->assertFalse($type->isInterface());
		$this->assertFalse($type->isTrait());
	}

	public function testReflectFileSimpleInterface()
	{
		$types = $this->reflector->reflectFile(__DIR__ . "/Fixtures/SimpleInterface.php");
		$this->assertCount(1, $types);

		$type = $types[0];
		$this->assertInstanceOf(Type::class, $type);
		$this->assertEquals(SimpleInterface::class, $type->getName());
		$this->assertTrue($type->isInterface());
		$this->assertFalse($type->isTrait());
	}

	public function testReflectFileSimpleTrait()
	{
		$types = $this->reflector->reflectFile(__DIR__ . "/Fixtures/SimpleTrait.php");
		$this->assertCount(1, $types);

		$type = $types[0];

		$this->assertInstanceOf(Type::class, $type);
		$this->assertEquals(SimpleTrait::class, $type->getName());
		$this->assertFalse($type->isInterface());
		$this->assertTrue($type->isTrait());
	}

	public function testReflectFileSameFileMultipleTimes()
	{
		// 1st time
		$types = $this->reflector->reflectFile(__DIR__ . "/Fixtures/SimpleClass.php");
		$this->assertCount(1, $types);
		$type = $types[0];
		$this->assertInstanceOf(Type::class, $type);
		$this->assertEquals(SimpleClass::class, $type->getName());

		// 2nd time
		$types = $this->reflector->reflectFile(__DIR__ . "/Fixtures/SimpleClass.php");
		$this->assertCount(1, $types);
		$type = $types[0];
		$this->assertInstanceOf(Type::class, $type);
		$this->assertEquals(SimpleClass::class, $type->getName());

		// 3rd time (just to be sure)
		$types = $this->reflector->reflectFile(__DIR__ . "/Fixtures/SimpleClass.php");
		$this->assertCount(1, $types);
		$type = $types[0];
		$this->assertInstanceOf(Type::class, $type);
		$this->assertEquals(SimpleClass::class, $type->getName());
	}

	public function testReflectFileMultipleFiles()
	{
		$types = $this->reflector->reflectFile(__DIR__ . "/Fixtures/SimpleClass.php");
		$this->assertCount(1, $types);
		$type = $types[0];
		$this->assertInstanceOf(Type::class, $type);
		$this->assertEquals(SimpleClass::class, $type->getName());

		$types = $this->reflector->reflectFile(__DIR__ . "/Fixtures/SimpleInterface.php");
		$this->assertCount(1, $types);
		$type = $types[0];
		$this->assertInstanceOf(Type::class, $type);
		$this->assertEquals(SimpleInterface::class, $type->getName());

		$types = $this->reflector->reflectFile(__DIR__ . "/Fixtures/SimpleTrait.php");
		$this->assertCount(1, $types);
		$type = $types[0];
		$this->assertInstanceOf(Type::class, $type);
		$this->assertEquals(SimpleTrait::class, $type->getName());
	}

}
