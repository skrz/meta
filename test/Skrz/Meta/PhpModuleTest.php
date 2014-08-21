<?php
namespace Skrz\Meta;

use Skrz\Meta\Fixtures\PHP\ClassWithArrayProperty;
use Skrz\Meta\Fixtures\PHP\ClassWithCustomOffsetProperty;
use Skrz\Meta\Fixtures\PHP\ClassWithManyArrayOffsetsPerProperty;
use Skrz\Meta\Fixtures\PHP\ClassWithNoProperty;
use Skrz\Meta\Fixtures\PHP\ClassWithPrivateProperty;
use Skrz\Meta\Fixtures\PHP\ClassWithPropertyReferencingClass;
use Skrz\Meta\Fixtures\PHP\ClassWithProtectedProperty;
use Skrz\Meta\Fixtures\PHP\ClassWithPublicProperty;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithArrayPropertyMeta;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithCustomOffsetPropertyMeta;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithManyArrayOffsetsPerPropertyMeta;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithNoPropertyMeta;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithPrivatePropertyMeta;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithPropertyReferencingClassMeta;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithProtectedPropertyMeta;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithPublicPropertyMeta;
use Skrz\Meta\Fixtures\PHP\PhpMetaSpec;
use Symfony\Component\Finder\Finder;

class PhpModuleTest extends \PHPUnit_Framework_TestCase
{

	public static function setUpBeforeClass()
	{
		$files = array_map(function (\SplFileInfo $file) {
			return $file->getPathname();
		}, iterator_to_array(
			(new Finder())
				->in(__DIR__ . "/Fixtures/PHP")
				->name("ClassWith*.php")
				->notName("*Meta*")
				->notName("ClassWithNonTransientPrivateProperty.php")
				->files()));

		$spec = new PhpMetaSpec();
		$spec->processFiles($files);
	}

	/**
	 * @expectedException \Skrz\Meta\MetaException
	 */
	public function testClassWithNonTransientPrivateProperty()
	{
		$spec = new PhpMetaSpec();
		$spec->processFiles(array(
			__DIR__ . "/Fixtures/PHP/ClassWithNonTransientPrivateProperty.php"
		));
	}

	public function testClassWithNoPropertyFromArray()
	{
		$this->assertInstanceOf(ClassWithNoPropertyMeta::class, ClassWithNoPropertyMeta::getInstance());

		$instance = ClassWithNoPropertyMeta::fromArray(array());
		$this->assertInstanceOf(ClassWithNoProperty::class, $instance);

		$this->assertSame($instance, ClassWithNoPropertyMeta::fromArray(array(), null, $instance));
	}

	public function testClassWithNoPropertyToArray()
	{
		$instance = new ClassWithNoProperty();
		$array = ClassWithNoPropertyMeta::toArray($instance);
		$this->assertEmpty($array);
	}

	public function testClassWithPublicPropertyFromArray()
	{
		$this->assertInstanceOf(ClassWithPublicPropertyMeta::class, ClassWithPublicPropertyMeta::getInstance());

		$instance = ClassWithPublicPropertyMeta::fromArray(array("property" => "value"));
		$this->assertInstanceOf(ClassWithPublicProperty::class, $instance);
		$this->assertEquals("value", $instance->property);

		$this->assertSame($instance, ClassWithPublicPropertyMeta::fromArray(array(), null, $instance));
	}

	public function testClassWithPublicPropertyToArray()
	{
		$instance = new ClassWithPublicProperty();
		$instance->property = "some value";
		$array = ClassWithPublicPropertyMeta::toArray($instance);
		$this->assertNotEmpty($array);
		$this->assertArrayHasKey("property", $array);
		$this->assertEquals("some value", $array["property"]);
	}

	public function testClassWithProtectedPropertyFromArray()
	{
		$this->assertInstanceOf(ClassWithProtectedPropertyMeta::class, ClassWithProtectedPropertyMeta::getInstance());

		$instance = ClassWithProtectedPropertyMeta::fromArray(array("property" => "value"));
		$this->assertInstanceOf(ClassWithProtectedProperty::class, $instance);
		$this->assertEquals("value", $instance->getProperty());

		$this->assertSame($instance, ClassWithProtectedPropertyMeta::fromArray(array(), null, $instance));
	}

	public function testClassWithProtectedPropertyToArray()
	{
		$instance = new ClassWithProtectedProperty();
		$instance->setProperty("some value");
		$array = ClassWithProtectedPropertyMeta::toArray($instance);
		$this->assertNotEmpty($array);
		$this->assertArrayHasKey("property", $array);
		$this->assertEquals("some value", $array["property"]);
	}

	public function testClassWithPrivatePropertyFromArray()
	{
		$this->assertInstanceOf(ClassWithPrivatePropertyMeta::class, ClassWithPrivatePropertyMeta::getInstance());

		$instance = ClassWithPrivatePropertyMeta::fromArray(array("property" => "value"));
		$this->assertInstanceOf(ClassWithPrivateProperty::class, $instance);
		$this->assertNull($instance->getProperty());

		$this->assertSame($instance, ClassWithPrivatePropertyMeta::fromArray(array(), null, $instance));
	}

	public function testClassWithPrivatePropertyToArray()
	{
		$instance = new ClassWithPrivateProperty();
		$instance->setProperty("some value");
		$array = ClassWithPrivatePropertyMeta::toArray($instance);
		$this->assertEmpty($array);
		$this->assertArrayNotHasKey("property", $array);
	}

	public function testClassWithCustomOffsetPropertyFromArray()
	{
		$this->assertInstanceOf(ClassWithCustomOffsetPropertyMeta::class, ClassWithCustomOffsetPropertyMeta::getInstance());

		$instance = ClassWithCustomOffsetPropertyMeta::fromArray(array("some-offset" => "value"));
		$this->assertInstanceOf(ClassWithCustomOffsetProperty::class, $instance);
		$this->assertEquals("value", $instance->property);

		$this->assertSame($instance, ClassWithCustomOffsetPropertyMeta::fromArray(array(), null, $instance));
	}

	public function testClassWithCustomOffsetPropertyToArray()
	{
		$instance = new ClassWithCustomOffsetProperty();
		$instance->property = "some value";
		$array = ClassWithCustomOffsetPropertyMeta::toArray($instance);
		$this->assertNotEmpty($array);
		$this->assertArrayHasKey("some-offset", $array);
		$this->assertEquals("some value", $array["some-offset"]);
	}

	public function testClassWithManyOffsetsPerPropertyFromArray()
	{
		$this->assertInstanceOf(ClassWithManyArrayOffsetsPerPropertyMeta::class, ClassWithManyArrayOffsetsPerPropertyMeta::getInstance());

		$instance = ClassWithManyArrayOffsetsPerPropertyMeta::fromArray(array("property" => "value"));
		$this->assertInstanceOf(ClassWithManyArrayOffsetsPerProperty::class, $instance);
		$this->assertEquals("value", $instance->property);
		$this->assertSame($instance, ClassWithManyArrayOffsetsPerPropertyMeta::fromArray(array(), null, $instance));

		$instance = ClassWithManyArrayOffsetsPerPropertyMeta::fromArray(array("foo" => "value"), "foo");
		$this->assertInstanceOf(ClassWithManyArrayOffsetsPerProperty::class, $instance);
		$this->assertEquals("value", $instance->property);
		$this->assertSame($instance, ClassWithManyArrayOffsetsPerPropertyMeta::fromArray(array(), "foo", $instance));

		$instance = ClassWithManyArrayOffsetsPerPropertyMeta::fromArray(array("bar" => "value"), "bar");
		$this->assertInstanceOf(ClassWithManyArrayOffsetsPerProperty::class, $instance);
		$this->assertEquals("value", $instance->property);
		$this->assertSame($instance, ClassWithManyArrayOffsetsPerPropertyMeta::fromArray(array(), "bar", $instance));
	}

	public function testClassWithManyOffsetsPerPropertyToArray()
	{
		$instance = new ClassWithManyArrayOffsetsPerProperty();
		$instance->property = "some value";

		$array = ClassWithManyArrayOffsetsPerPropertyMeta::toArray($instance);
		$this->assertNotEmpty($array);
		$this->assertArrayHasKey("property", $array);
		$this->assertEquals("some value", $array["property"]);

		$array = ClassWithManyArrayOffsetsPerPropertyMeta::toArray($instance, "foo");
		$this->assertNotEmpty($array);
		$this->assertArrayHasKey("foo", $array);
		$this->assertEquals("some value", $array["foo"]);

		$array = ClassWithManyArrayOffsetsPerPropertyMeta::toArray($instance, "bar");
		$this->assertNotEmpty($array);
		$this->assertArrayHasKey("bar", $array);
		$this->assertEquals("some value", $array["bar"]);
	}

	public function testClassWithPropertyReferencingClassFromArray()
	{
		$this->assertInstanceOf(ClassWithPropertyReferencingClassMeta::class, ClassWithPropertyReferencingClassMeta::getInstance());

		$instance = ClassWithPropertyReferencingClassMeta::fromArray(array(
			"classWithPublicProperty" => array(
				"property" => "value"
			)
		));
		$this->assertInstanceOf(ClassWithPropertyReferencingClass::class, $instance);
		$this->assertInstanceOf(ClassWithPublicProperty::class, $instance->classWithPublicProperty);
		$this->assertEquals("value", $instance->classWithPublicProperty->property);

		$instanceAgain = ClassWithPropertyReferencingClassMeta::fromArray(array(
			"classWithPublicProperty" => array(
				"property" => "other value"
			)
		), null, $instance);
		$this->assertSame($instance, $instanceAgain);
		$this->assertSame($instance->classWithPublicProperty, $instanceAgain->classWithPublicProperty);
	}

	public function testClassWithPropertyReferencingClassToArray()
	{
		$instance = new ClassWithPropertyReferencingClass();
		$instance->classWithPublicProperty = new ClassWithPublicProperty();
		$instance->classWithPublicProperty->property = "some value";

		$array = ClassWithPropertyReferencingClassMeta::toArray($instance);
		$this->assertNotEmpty($array);
		$this->assertArrayHasKey("classWithPublicProperty", $array);
		$this->assertNotEmpty($array["classWithPublicProperty"]);
		$this->assertArrayHasKey("property", $array["classWithPublicProperty"]);
		$this->assertEquals("some value", $array["classWithPublicProperty"]["property"]);
	}

	public function testClassWithArrayPropertyFromArray()
	{
		$this->assertInstanceOf(ClassWithArrayPropertyMeta::class, ClassWithArrayPropertyMeta::getInstance());

		$instance = ClassWithArrayPropertyMeta::fromArray(array("array" => array("foo" => array("bar" => "baz"))));
		$this->assertInstanceOf(ClassWithArrayProperty::class, $instance);
		$this->assertNotEmpty($instance->array);
		$this->assertArrayHasKey("foo", $instance->array);
		$this->assertArrayHasKey("bar", $instance->array["foo"]);
		$this->assertEquals("baz", $instance->array["foo"]["bar"]);

		$this->assertSame($instance, ClassWithArrayPropertyMeta::fromArray(array(), null, $instance));
	}

	public function testClassWithArrayPropertyToArray()
	{
		$instance = new ClassWithArrayProperty();
		$instance->array = array("foo" => array("bar" => "baz"));
		$array = ClassWithArrayPropertyMeta::toArray($instance);
		$this->assertNotEmpty($array);
		$this->assertArrayHasKey("array", $array);
		$this->assertArrayHasKey("foo", $array["array"]);
		$this->assertArrayHasKey("bar", $array["array"]["foo"]);
		$this->assertEquals("baz", $array["array"]["foo"]["bar"]);
	}

}
