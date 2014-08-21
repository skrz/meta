<?php
namespace Skrz\Meta;

use Skrz\Meta\Fixtures\JSON\ClassWithCustomNameProperty;
use Skrz\Meta\Fixtures\JSON\ClassWithNoProperty;
use Skrz\Meta\Fixtures\JSON\ClassWithPublicProperty;
use Skrz\Meta\Fixtures\JSON\JsonMetaSpec;
use Skrz\Meta\Fixtures\JSON\Meta\ClassWithCustomNamePropertyMeta;
use Skrz\Meta\Fixtures\JSON\Meta\ClassWithNoPropertyMeta;
use Skrz\Meta\Fixtures\JSON\Meta\ClassWithPublicPropertyMeta;
use Symfony\Component\Finder\Finder;

class JsonModuleTest extends \PHPUnit_Framework_TestCase
{

	public static function setUpBeforeClass()
	{
		$files = array_map(function (\SplFileInfo $file) {
			return $file->getPathname();
		}, iterator_to_array(
			(new Finder())
				->in(__DIR__ . "/Fixtures/JSON")
				->name("ClassWith*.php")
				->notName("*Meta*")
				->files()));

		$spec = new JsonMetaSpec();
		$spec->processFiles($files);
	}

	public function testClassWithNoPropertyFromJson()
	{
		$this->assertInstanceOf(ClassWithNoPropertyMeta::class, ClassWithNoPropertyMeta::getInstance());

		$instance = ClassWithNoPropertyMeta::fromJson(array());
		$this->assertInstanceOf(ClassWithNoProperty::class, $instance);

		$instance = ClassWithNoPropertyMeta::fromJson("{}");
		$this->assertInstanceOf(ClassWithNoProperty::class, $instance);
	}

	public function testClassWithNoPropertyToJson()
	{
		$instance = new ClassWithNoProperty();
		$json = ClassWithNoPropertyMeta::toJson($instance);
		$this->assertEmpty((array)$json);
	}

	public function testClassWithNoPropertyToJsonString()
	{
		$instance = new ClassWithNoProperty();
		$json = ClassWithNoPropertyMeta::toJsonString($instance);
		$this->assertEquals("{}", $json);
	}

	public function testClassWithPublicPropertyFromJson()
	{
		$this->assertInstanceOf(ClassWithPublicPropertyMeta::class, ClassWithPublicPropertyMeta::getInstance());

		$instance = ClassWithPublicPropertyMeta::fromJson(array());
		$this->assertInstanceOf(ClassWithPublicProperty::class, $instance);
		$this->assertEquals(null, $instance->property);

		$instance = ClassWithPublicPropertyMeta::fromJson(array("property" => "value"));
		$this->assertInstanceOf(ClassWithPublicProperty::class, $instance);
		$this->assertEquals("value", $instance->property);

		$instance = ClassWithPublicPropertyMeta::fromJson("{}");
		$this->assertInstanceOf(ClassWithPublicProperty::class, $instance);
		$this->assertEquals(null, $instance->property);

		$instance = ClassWithPublicPropertyMeta::fromJson('{"property":"value"}');
		$this->assertInstanceOf(ClassWithPublicProperty::class, $instance);
		$this->assertEquals("value", $instance->property);
	}

	public function testClassWithPublicPropertyToJson()
	{
		$instance = new ClassWithPublicProperty();
		$json = ClassWithPublicPropertyMeta::toJson($instance);
		$this->assertNotEmpty($json);
		$this->assertObjectHasAttribute("property", $json);
		$this->assertNull($json->property);

		$instance->property = "value";
		$json = ClassWithPublicPropertyMeta::toJson($instance);
		$this->assertNotEmpty($json);
		$this->assertObjectHasAttribute("property", $json);
		$this->assertEquals("value", $json->property);
	}

	public function testClassWithPublicPropertyToJsonString()
	{
		$instance = new ClassWithPublicProperty();
		$json = ClassWithPublicPropertyMeta::toJsonString($instance);
		$this->assertEquals('{"property":null}', $json);

		$instance->property = "value";
		$json = ClassWithPublicPropertyMeta::toJsonString($instance);
		$this->assertEquals('{"property":"value"}', $json);
	}

	public function testClassWithCustomNamePropertyFromJson()
	{
		$this->assertInstanceOf(ClassWithCustomNamePropertyMeta::class, ClassWithCustomNamePropertyMeta::getInstance());

		$instance = ClassWithCustomNamePropertyMeta::fromJson(array());
		$this->assertInstanceOf(ClassWithCustomNameProperty::class, $instance);
		$this->assertEquals(null, $instance->getSomeProperty());

		$instance = ClassWithCustomNamePropertyMeta::fromJson(array("some_property" => "some value"));
		$this->assertInstanceOf(ClassWithCustomNameProperty::class, $instance);
		$this->assertEquals("some value", $instance->getSomeProperty());

		$instance = ClassWithCustomNamePropertyMeta::fromJson('{}');
		$this->assertInstanceOf(ClassWithCustomNameProperty::class, $instance);
		$this->assertEquals(null, $instance->getSomeProperty());

		$instance = ClassWithCustomNamePropertyMeta::fromJson('{"some_property":"some value"}');
		$this->assertInstanceOf(ClassWithCustomNameProperty::class, $instance);
		$this->assertEquals("some value", $instance->getSomeProperty());
	}

	public function testClassWithCustomNamePropertyToJson()
	{
		$instance = new ClassWithCustomNameProperty();
		$json = ClassWithCustomNamePropertyMeta::toJson($instance);
		$this->assertNotEmpty($json);
		$this->assertObjectHasAttribute("some_property", $json);
		$this->assertNull($json->some_property);

		$instance->setSomeProperty("value");
		$json = ClassWithCustomNamePropertyMeta::toJson($instance);
		$this->assertNotEmpty($json);
		$this->assertObjectHasAttribute("some_property", $json);
		$this->assertEquals("value", $json->some_property);
	}

	public function testClassWithCustomNamePropertyToJsonString()
	{
		$instance = new ClassWithCustomNameProperty();
		$json = ClassWithCustomNamePropertyMeta::toJsonString($instance);
		$this->assertEquals('{"some_property":null}', $json);

		$instance->setSomeProperty("value");
		$json = ClassWithCustomNamePropertyMeta::toJsonString($instance);
		$this->assertEquals('{"some_property":"value"}', $json);
	}

}
