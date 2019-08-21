<?php
namespace Skrz\Meta;

use PHPUnit\Framework\TestCase;
use Skrz\Meta\Fields\Fields;
use Skrz\Meta\Fixtures\JSON\ClassWithArrayOfJsonRoot;
use Skrz\Meta\Fixtures\JSON\ClassWithCustomNameProperty;
use Skrz\Meta\Fixtures\JSON\ClassWithDiscriminatorValueA;
use Skrz\Meta\Fixtures\JSON\ClassWithDiscriminatorValueB;
use Skrz\Meta\Fixtures\JSON\ClassWithMoreProperties;
use Skrz\Meta\Fixtures\JSON\ClassWithNoProperty;
use Skrz\Meta\Fixtures\JSON\ClassWithPublicProperty;
use Skrz\Meta\Fixtures\JSON\JsonMetaSpec;
use Skrz\Meta\Fixtures\JSON\Meta\ClassWithArrayOfJsonRootMeta;
use Skrz\Meta\Fixtures\JSON\Meta\ClassWithCustomNamePropertyMeta;
use Skrz\Meta\Fixtures\JSON\Meta\ClassWithDiscriminatorMapMeta;
use Skrz\Meta\Fixtures\JSON\Meta\ClassWithMorePropertiesMeta;
use Skrz\Meta\Fixtures\JSON\Meta\ClassWithNoPropertyMeta;
use Skrz\Meta\Fixtures\JSON\Meta\ClassWithPublicPropertyMeta;
use Symfony\Component\Finder\Finder;

class JsonModuleTest extends TestCase
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
				->files()
		));

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
		$this->assertEquals("{}", $json);
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
		$this->assertEquals('{}', $json);

		$instance->property = "value";
		$json = ClassWithPublicPropertyMeta::toJson($instance);
		$this->assertEquals('{"property":"value"}', $json);
	}

	public function testClassWithPublicPropertyToJsonString()
	{
		$instance = new ClassWithPublicProperty();
		$json = ClassWithPublicPropertyMeta::toJsonString($instance);
		$this->assertEquals('{}', $json);

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
		$this->assertEquals('{}', $json);

		$instance->setSomeProperty("value");
		$json = ClassWithCustomNamePropertyMeta::toJson($instance);
		$this->assertEquals('{"some_property":"value"}', $json);
	}

	public function testClassWithCustomNamePropertyToJsonString()
	{
		$instance = new ClassWithCustomNameProperty();
		$json = ClassWithCustomNamePropertyMeta::toJsonString($instance);
		$this->assertEquals('{}', $json);

		$instance->setSomeProperty("value");
		$json = ClassWithCustomNamePropertyMeta::toJsonString($instance);
		$this->assertEquals('{"some_property":"value"}', $json);
	}

	public function testClassWithDiscriminatorMapFromJson()
	{
		$this->assertInstanceOf(ClassWithDiscriminatorMapMeta::class, ClassWithDiscriminatorMapMeta::getInstance());

		/** @var ClassWithDiscriminatorValueA $aInstance */
		$aInstance = ClassWithDiscriminatorMapMeta::fromJson(array("value" => "a", "a" => 21, "b" => 42));
		$this->assertInstanceOf(ClassWithDiscriminatorValueA::class, $aInstance);
		$this->assertEquals("a", $aInstance->value);
		$this->assertEquals(21, $aInstance->a);

		/** @var ClassWithDiscriminatorValueA $aInstance */
		$aInstance = ClassWithDiscriminatorMapMeta::fromJson('{"value":"a","a":1,"b":2}');
		$this->assertInstanceOf(ClassWithDiscriminatorValueA::class, $aInstance);
		$this->assertEquals("a", $aInstance->value);
		$this->assertEquals(1, $aInstance->a);

		/** @var ClassWithDiscriminatorValueA $aInstance */
		$aInstance = ClassWithDiscriminatorMapMeta::fromJson(array("a" => array("a" => 21)), "top");
		$this->assertInstanceOf(ClassWithDiscriminatorValueA::class, $aInstance);
		$this->assertNull($aInstance->value);
		$this->assertEquals(21, $aInstance->a);

		/** @var ClassWithDiscriminatorValueA $aInstance */
		$aInstance = ClassWithDiscriminatorMapMeta::fromJson('{"a":{"a":1}}', "top");
		$this->assertInstanceOf(ClassWithDiscriminatorValueA::class, $aInstance);
		$this->assertNull($aInstance->value);
		$this->assertEquals(1, $aInstance->a);

		/** @var ClassWithDiscriminatorValueB $bInstance */
		$bInstance = ClassWithDiscriminatorMapMeta::fromJson(array("value" => "b", "a" => 21, "b" => 42));
		$this->assertInstanceOf(ClassWithDiscriminatorValueB::class, $bInstance);
		$this->assertEquals("b", $bInstance->value);
		$this->assertEquals(42, $bInstance->b);

		/** @var ClassWithDiscriminatorValueB $bInstance */
		$bInstance = ClassWithDiscriminatorMapMeta::fromJson('{"value":"b","a":1,"b":2}');
		$this->assertInstanceOf(ClassWithDiscriminatorValueB::class, $bInstance);
		$this->assertEquals("b", $bInstance->value);
		$this->assertEquals(2, $bInstance->b);

		/** @var ClassWithDiscriminatorValueB $bInstance */
		$bInstance = ClassWithDiscriminatorMapMeta::fromJson(array("b" => array("b" => 42)), "top");
		$this->assertInstanceOf(ClassWithDiscriminatorValueB::class, $bInstance);
		$this->assertNull($bInstance->value);
		$this->assertEquals(42, $bInstance->b);

		/** @var ClassWithDiscriminatorValueB $bInstance */
		$bInstance = ClassWithDiscriminatorMapMeta::fromJson('{"b":{"b":2}}', "top");
		$this->assertInstanceOf(ClassWithDiscriminatorValueB::class, $bInstance);
		$this->assertNull($bInstance->value);
		$this->assertEquals(2, $bInstance->b);
	}

	public function testClassWithDiscriminatorMapToJsonString()
	{
		$aInstance = new ClassWithDiscriminatorValueA();
		$aInstance->a = 42;

		$bInstance = new ClassWithDiscriminatorValueB();
		$bInstance->b = 21;

		$this->assertEquals('{"a":42,"value":"a"}', ClassWithDiscriminatorMapMeta::toJson($aInstance));
		$this->assertEquals('{"a":{"a":42}}', ClassWithDiscriminatorMapMeta::toJson($aInstance, "top"));
		$this->assertEquals('{"b":21,"value":"b"}', ClassWithDiscriminatorMapMeta::toJson($bInstance));
		$this->assertEquals('{"b":{"b":21}}', ClassWithDiscriminatorMapMeta::toJson($bInstance, "top"));
	}

	public function testClassWithArrayOfJsonRootFromArrayOfJson()
	{
		$this->assertInstanceOf(ClassWithArrayOfJsonRootMeta::class, ClassWithArrayOfJsonRootMeta::getInstance());

		$instance = ClassWithArrayOfJsonRootMeta::fromArrayOfJson(array("direct" => "foo", "nested" => '{"property":"bar"}'));
		$this->assertInstanceOf(ClassWithArrayOfJsonRoot::class, $instance);
		$this->assertEquals("foo", $instance->direct);
		$this->assertNotNull($instance->nested);
		$this->assertEquals("bar", $instance->nested->property);

		ClassWithArrayOfJsonRootMeta::fromArrayOfJson(array("direct" => "qux"), null, $instance);
		$this->assertInstanceOf(ClassWithArrayOfJsonRoot::class, $instance);
		$this->assertEquals("qux", $instance->direct);
		$this->assertNotNull($instance->nested);
		$this->assertEquals("bar", $instance->nested->property);

		ClassWithArrayOfJsonRootMeta::fromArrayOfJson(array("nested" => '{"property":"baz"}'), null, $instance);
		$this->assertInstanceOf(ClassWithArrayOfJsonRoot::class, $instance);
		$this->assertEquals("qux", $instance->direct);
		$this->assertNotNull($instance->nested);
		$this->assertEquals("baz", $instance->nested->property);

		ClassWithArrayOfJsonRootMeta::fromArrayOfJson(array("arrayOfStrings" => '[]'), null, $instance);
		$this->assertInstanceOf(ClassWithArrayOfJsonRoot::class, $instance);
		$this->assertEquals("qux", $instance->direct);
		$this->assertNotNull($instance->nested);
		$this->assertEquals("baz", $instance->nested->property);
		$this->assertEquals(array(), $instance->arrayOfStrings);

		ClassWithArrayOfJsonRootMeta::fromArrayOfJson(array("arrayOfStrings" => '["zzz"]'), null, $instance);
		$this->assertInstanceOf(ClassWithArrayOfJsonRoot::class, $instance);
		$this->assertEquals("qux", $instance->direct);
		$this->assertNotNull($instance->nested);
		$this->assertEquals("baz", $instance->nested->property);
		$this->assertEquals(array("zzz"), $instance->arrayOfStrings);
	}

	public function testClassWithArrayOfJsonRootToArrayOfJson()
	{
		$instance = new ClassWithArrayOfJsonRoot();

		$instance->direct = "foo";
		$this->assertEquals(array("direct" => "foo"), ClassWithArrayOfJsonRootMeta::toArrayOfJson($instance));

		$instance->nested = new ClassWithPublicProperty();
		$this->assertEquals(array("direct" => "foo", "nested" => '{}'), ClassWithArrayOfJsonRootMeta::toArrayOfJson($instance));

		$instance->nested->property = "bar";
		$this->assertEquals(array("direct" => "foo", "nested" => '{"property":"bar"}'), ClassWithArrayOfJsonRootMeta::toArrayOfJson($instance));

		$instance->nested = null;
		$this->assertEquals(array("direct" => "foo"), ClassWithArrayOfJsonRootMeta::toArrayOfJson($instance));

		$instance->arrayOfStrings = array();
		$this->assertEquals(array("direct" => "foo", "arrayOfStrings" => '[]'), ClassWithArrayOfJsonRootMeta::toArrayOfJson($instance));

		$instance->arrayOfStrings[] = "qux";
		$this->assertEquals(array("direct" => "foo", "arrayOfStrings" => '["qux"]'), ClassWithArrayOfJsonRootMeta::toArrayOfJson($instance));

		$instance->arrayOfStrings[] = "baz";
		$this->assertEquals(array("direct" => "foo", "arrayOfStrings" => '["qux","baz"]'), ClassWithArrayOfJsonRootMeta::toArrayOfJson($instance));
	}

	public function testClassWithMorePropertiesFiltered()
	{
		$instance = new ClassWithMoreProperties();
		$instance->a = "foo1";
		$instance->b = "foo2";
		$instance->c = "foo3";
		$instance->d = "foo4";
		$instance->e = "foo5";

		$this->assertEquals(
			'{"a":"foo1","b":"foo2","c":"foo3","d":"foo4","e":"foo5"}',
			ClassWithMorePropertiesMeta::toJson($instance)
		);

		$this->assertEquals(
			'{"a":"foo1"}',
			ClassWithMorePropertiesMeta::toJson($instance, null, [
				"a" => true,
			])
		);

		$this->assertEquals(
			'{"b":"foo2","c":"foo3","d":"foo4"}',
			ClassWithMorePropertiesMeta::toJson($instance, null, [
				"b" => true,
				"c" => true,
				"d" => true,
			])
		);

		$instance2 = new ClassWithMoreProperties();
		$instance2->a = "foo6";
		$instance->f = $instance2;

		$this->assertEquals(
			'{"e":"foo5","f":{"a":"foo6","b":null}}',
			ClassWithMorePropertiesMeta::toJson($instance, null, [
				"e" => true,
				"f" => [
					"a" => true,
					"b" => true,
				],
			])
		);

		$instance3 = new ClassWithMoreProperties();
		$instance3->a = "foo7";
		$instance->g = [$instance2, $instance3];

		$this->assertEquals(
			'{"g":[{"a":"foo6"},{"a":"foo7"}]}',
			ClassWithMorePropertiesMeta::toJson($instance, null, [
				"g" => [
					"a" => true,
				],
			])
		);
	}

	public function testClassWithMorePropertiesFilteredFromArray()
	{
		$instance = new ClassWithMoreProperties();
		$instance->a = "foo1";
		$instance->b = "foo2";
		$instance->c = "foo3";
		$instance->d = "foo4";
		$instance->e = "foo5";

		$this->assertEquals(
			'{"a":"foo1","b":"foo2","c":"foo3","d":"foo4","e":"foo5"}',
			ClassWithMorePropertiesMeta::toJson($instance)
		);

		$this->assertEquals(
			'{"a":"foo1"}',
			ClassWithMorePropertiesMeta::toJson($instance, null, Fields::fromArray([
				"a" => true,
			]))
		);

		$this->assertEquals(
			'{"b":"foo2","c":"foo3","d":"foo4"}',
			ClassWithMorePropertiesMeta::toJson($instance, null, Fields::fromArray([
				"b" => true,
				"c" => true,
				"d" => true,
			]))
		);

		$instance2 = new ClassWithMoreProperties();
		$instance2->a = "foo6";
		$instance->f = $instance2;

		$this->assertEquals(
			'{"e":"foo5","f":{"a":"foo6","b":null}}',
			ClassWithMorePropertiesMeta::toJson($instance, null, Fields::fromArray([
				"e" => true,
				"f" => [
					"a" => true,
					"b" => true,
				],
			]))
		);

		$instance3 = new ClassWithMoreProperties();
		$instance3->a = "foo7";
		$instance->g = [$instance2, $instance3];

		$this->assertEquals(
			'{"g":[{"a":"foo6"},{"a":"foo7"}]}',
			ClassWithMorePropertiesMeta::toJson($instance, null, Fields::fromArray([
				"g" => [
					"a" => true,
				],
			]))
		);
	}

	public function testClassWithMorePropertiesFilteredByFieldsFromString()
	{
		$instance = new ClassWithMoreProperties();
		$instance->a = "foo1";
		$instance->b = "foo2";
		$instance->c = "foo3";
		$instance->d = "foo4";
		$instance->e = "foo5";

		$this->assertEquals(
			'{"a":"foo1","b":"foo2","c":"foo3","d":"foo4","e":"foo5"}',
			ClassWithMorePropertiesMeta::toJson($instance)
		);

		$this->assertEquals(
			'{"a":"foo1"}',
			ClassWithMorePropertiesMeta::toJson($instance, null, Fields::fromString("a"))
		);

		$this->assertEquals(
			'{"b":"foo2","c":"foo3","d":"foo4"}',
			ClassWithMorePropertiesMeta::toJson($instance, null, Fields::fromString("b,c,d"))
		);

		$instance2 = new ClassWithMoreProperties();
		$instance2->a = "foo6";
		$instance->f = $instance2;

		$this->assertEquals(
			'{"e":"foo5","f":{"a":"foo6","b":null}}',
			ClassWithMorePropertiesMeta::toJson($instance, null, Fields::fromString("e,f{a,b}"))
		);

		$instance3 = new ClassWithMoreProperties();
		$instance3->a = "foo7";
		$instance->g = [$instance2, $instance3];

		$this->assertEquals(
			'{"g":[{"a":"foo6"},{"a":"foo7"}]}',
			ClassWithMorePropertiesMeta::toJson($instance, null, Fields::fromString("g{a}"))
		);
	}

}
