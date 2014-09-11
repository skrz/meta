<?php
namespace Skrz\Meta;

use Skrz\Meta\Fixtures\JSON\ClassWithCustomNameProperty;
use Skrz\Meta\Fixtures\JSON\ClassWithDiscriminatorValueA;
use Skrz\Meta\Fixtures\JSON\ClassWithDiscriminatorValueB;
use Skrz\Meta\Fixtures\JSON\ClassWithNoProperty;
use Skrz\Meta\Fixtures\JSON\ClassWithPublicProperty;
use Skrz\Meta\Fixtures\JSON\JsonMetaSpec;
use Skrz\Meta\Fixtures\JSON\Meta\ClassWithCustomNamePropertyMeta;
use Skrz\Meta\Fixtures\JSON\Meta\ClassWithDiscriminatorMapMeta;
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
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Json\\Meta\\ClassWithNoPropertyMeta", ClassWithNoPropertyMeta::getInstance());

		$instance = ClassWithNoPropertyMeta::fromJson(array());
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Json\\ClassWithNoProperty", $instance);

		$instance = ClassWithNoPropertyMeta::fromJson("{}");
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Json\\ClassWithNoProperty", $instance);
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
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Json\\Meta\\ClassWithPublicPropertyMeta", ClassWithPublicPropertyMeta::getInstance());

		$instance = ClassWithPublicPropertyMeta::fromJson(array());
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Json\\ClassWithPublicProperty", $instance);
		$this->assertEquals(null, $instance->property);

		$instance = ClassWithPublicPropertyMeta::fromJson(array("property" => "value"));
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Json\\ClassWithPublicProperty", $instance);
		$this->assertEquals("value", $instance->property);

		$instance = ClassWithPublicPropertyMeta::fromJson("{}");
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Json\\ClassWithPublicProperty", $instance);
		$this->assertEquals(null, $instance->property);

		$instance = ClassWithPublicPropertyMeta::fromJson('{"property":"value"}');
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Json\\ClassWithPublicProperty", $instance);
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
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Json\\Meta\\ClassWithCustomNamePropertyMeta", ClassWithCustomNamePropertyMeta::getInstance());

		$instance = ClassWithCustomNamePropertyMeta::fromJson(array());
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Json\\ClassWithCustomNameProperty", $instance);
		$this->assertEquals(null, $instance->getSomeProperty());

		$instance = ClassWithCustomNamePropertyMeta::fromJson(array("some_property" => "some value"));
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Json\\ClassWithCustomNameProperty", $instance);
		$this->assertEquals("some value", $instance->getSomeProperty());

		$instance = ClassWithCustomNamePropertyMeta::fromJson('{}');
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Json\\ClassWithCustomNameProperty", $instance);
		$this->assertEquals(null, $instance->getSomeProperty());

		$instance = ClassWithCustomNamePropertyMeta::fromJson('{"some_property":"some value"}');
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Json\\ClassWithCustomNameProperty", $instance);
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

	public function testClassWithDiscriminatorMapFromJson()
	{
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\JSON\\Meta\\ClassWithDiscriminatorMapMeta", ClassWithDiscriminatorMapMeta::getInstance());

		/** @var ClassWithDiscriminatorValueA $aInstance */
		$aInstance = ClassWithDiscriminatorMapMeta::fromJson(array("value" => "a", "a" => 21, "b" => 42));
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\JSON\\ClassWithDiscriminatorValueA", $aInstance);
		$this->assertEquals("a", $aInstance->value);
		$this->assertEquals(21, $aInstance->a);

		/** @var ClassWithDiscriminatorValueA $aInstance */
		$aInstance = ClassWithDiscriminatorMapMeta::fromJson('{"value":"a","a":1,"b":2}');
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\JSON\\ClassWithDiscriminatorValueA", $aInstance);
		$this->assertEquals("a", $aInstance->value);
		$this->assertEquals(1, $aInstance->a);

		/** @var ClassWithDiscriminatorValueA $aInstance */
		$aInstance = ClassWithDiscriminatorMapMeta::fromJson(array("a" => array("a" => 21)), "top");
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\JSON\\ClassWithDiscriminatorValueA", $aInstance);
		$this->assertNull($aInstance->value);
		$this->assertEquals(21, $aInstance->a);

		/** @var ClassWithDiscriminatorValueA $aInstance */
		$aInstance = ClassWithDiscriminatorMapMeta::fromJson('{"a":{"a":1}}', "top");
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\JSON\\ClassWithDiscriminatorValueA", $aInstance);
		$this->assertNull($aInstance->value);
		$this->assertEquals(1, $aInstance->a);

		/** @var ClassWithDiscriminatorValueB $bInstance */
		$bInstance = ClassWithDiscriminatorMapMeta::fromJson(array("value" => "b", "a" => 21, "b" => 42));
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\JSON\\ClassWithDiscriminatorValueB", $bInstance);
		$this->assertEquals("b", $bInstance->value);
		$this->assertEquals(42, $bInstance->b);

		/** @var ClassWithDiscriminatorValueB $bInstance */
		$bInstance = ClassWithDiscriminatorMapMeta::fromJson('{"value":"b","a":1,"b":2}');
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\JSON\\ClassWithDiscriminatorValueB", $bInstance);
		$this->assertEquals("b", $bInstance->value);
		$this->assertEquals(2, $bInstance->b);

		/** @var ClassWithDiscriminatorValueB $bInstance */
		$bInstance = ClassWithDiscriminatorMapMeta::fromJson(array("b" => array("b" => 42)), "top");
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\JSON\\ClassWithDiscriminatorValueB", $bInstance);
		$this->assertNull($bInstance->value);
		$this->assertEquals(42, $bInstance->b);

		/** @var ClassWithDiscriminatorValueB $bInstance */
		$bInstance = ClassWithDiscriminatorMapMeta::fromJson('{"b":{"b":2}}', "top");
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\JSON\\ClassWithDiscriminatorValueB", $bInstance);
		$this->assertNull($bInstance->value);
		$this->assertEquals(2, $bInstance->b);
	}

}
