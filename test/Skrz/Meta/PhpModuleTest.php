<?php
namespace Skrz\Meta;

use PHPUnit\Framework\TestCase;
use Skrz\Meta\Fields\Fields;
use Skrz\Meta\Fixtures\PHP\ArrayCollection;
use Skrz\Meta\Fixtures\PHP\ClassWithArrayProperty;
use Skrz\Meta\Fixtures\PHP\ClassWithCustomOffsetProperty;
use Skrz\Meta\Fixtures\PHP\ClassWithDatetimeProperty;
use Skrz\Meta\Fixtures\PHP\ClassWithDiscriminatorValueA;
use Skrz\Meta\Fixtures\PHP\ClassWithDiscriminatorValueB;
use Skrz\Meta\Fixtures\PHP\ClassWithManyArrayOffsetsPerProperty;
use Skrz\Meta\Fixtures\PHP\ClassWithMoreProperties;
use Skrz\Meta\Fixtures\PHP\ClassWithNoProperty;
use Skrz\Meta\Fixtures\PHP\ClassWithPrivateProperty;
use Skrz\Meta\Fixtures\PHP\ClassWithPropertyReferencingClass;
use Skrz\Meta\Fixtures\PHP\ClassWithProtectedProperty;
use Skrz\Meta\Fixtures\PHP\ClassWithPublicProperty;
use Skrz\Meta\Fixtures\PHP\ClassWithRecursiveProperty;
use Skrz\Meta\Fixtures\PHP\ClassWithTransientProperty;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithArrayPropertyMeta;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithCustomOffsetPropertyMeta;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithDatetimePropertyMeta;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithDiscriminatorMapMeta;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithManyArrayOffsetsPerPropertyMeta;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithMorePropertiesMeta;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithNoPropertyMeta;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithPrivatePropertyMeta;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithPropertyReferencingClassMeta;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithProtectedPropertyMeta;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithPublicPropertyMeta;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithRecursivePropertyMeta;
use Skrz\Meta\Fixtures\PHP\Meta\ClassWithTransientPropertyMeta;
use Skrz\Meta\Fixtures\PHP\PhpMetaSpec;
use Symfony\Component\Finder\Finder;

class PhpModuleTest extends TestCase
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
				->files()
		));

		$spec = new PhpMetaSpec();
		$spec->processFiles($files);
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
		$this->assertEquals("value", $instance->getProperty());

		$this->assertSame($instance, ClassWithPrivatePropertyMeta::fromArray(array(), null, $instance));
	}

	public function testClassWithPrivatePropertyToArray()
	{
		$instance = new ClassWithPrivateProperty();
		$instance->setProperty("some value");
		$array = ClassWithPrivatePropertyMeta::toArray($instance);
		$this->assertNotEmpty($array);
		$this->assertArrayHasKey("property", $array);
		$this->assertEquals("some value", $array["property"]);
	}

	public function testClassWithTransientPropertyFromArray()
	{
		$this->assertInstanceOf(ClassWithTransientPropertyMeta::class, ClassWithTransientPropertyMeta::getInstance());

		$instance = ClassWithTransientPropertyMeta::fromArray(array("property" => "value"));
		$this->assertInstanceOf(ClassWithTransientProperty::class, $instance);
		$this->assertNull($instance->property);

		$this->assertSame($instance, ClassWithTransientPropertyMeta::fromArray(array(), null, $instance));
	}

	public function testClassWithTransientPropertyToArray()
	{
		$instance = new ClassWithTransientProperty();
		$instance->property = "some value";
		$array = ClassWithTransientPropertyMeta::toArray($instance);
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

	public function testClassWithArrayPropertyFromArrayWithArrayCollection()
	{
		$this->assertInstanceOf(ClassWithArrayPropertyMeta::class, ClassWithArrayPropertyMeta::getInstance());

		$instance = ClassWithArrayPropertyMeta::fromArray(array("array" => new ArrayCollection(array("foo" => array("bar" => "baz")))));
		$this->assertInstanceOf(ClassWithArrayProperty::class, $instance);
		$this->assertNotEmpty($instance->array);
		$this->assertArrayHasKey("foo", $instance->array);
		$this->assertArrayHasKey("bar", $instance->array["foo"]);
		$this->assertEquals("baz", $instance->array["foo"]["bar"]);

		$this->assertSame($instance, ClassWithArrayPropertyMeta::fromArray(array(), null, $instance));
	}

	public function testClassWithArrayPropertyToArrayWithArrayCollection()
	{
		$instance = new ClassWithArrayProperty();
		$instance->array = new ArrayCollection(array("foo" => array("bar" => "baz")));
		$array = ClassWithArrayPropertyMeta::toArray($instance);
		$this->assertNotEmpty($array);
		$this->assertArrayHasKey("array", $array);
		$this->assertArrayHasKey("foo", $array["array"]);
		$this->assertArrayHasKey("bar", $array["array"]["foo"]);
		$this->assertEquals("baz", $array["array"]["foo"]["bar"]);
	}

	public function testClassWithDatetimePropertyFromArray()
	{
		$this->assertInstanceOf(ClassWithDatetimePropertyMeta::class, ClassWithDatetimePropertyMeta::getInstance());

		$d = new \DateTime();
		$instance = ClassWithDatetimePropertyMeta::fromArray(array("datetime" => $d->format("Y-m-d H:i:s")));

		$this->assertNotNull($instance->datetime);
		$this->assertEquals($d->format(\DateTime::ATOM), $instance->datetime->format(\DateTime::ATOM));
	}

	public function testClassWithDatetimePropertyToArray()
	{
		$d = new \DateTime();
		$instance = new ClassWithDatetimeProperty();
		$instance->datetime = $d;

		$array = ClassWithDatetimePropertyMeta::toArray($instance);

		$this->assertArrayHasKey("datetime", $array);
		$this->assertEquals($d->format("Y-m-d H:i:s"), $array["datetime"]);
	}

	public function testClassWithDiscriminatorMapFromArray()
	{
		$this->assertInstanceOf(ClassWithDiscriminatorMapMeta::class, ClassWithDiscriminatorMapMeta::getInstance());

		/** @var ClassWithDiscriminatorValueA $aInstance */
		$aInstance = ClassWithDiscriminatorMapMeta::fromArray(array("value" => "a", "a" => 21, "b" => 42));
		$this->assertInstanceOf(ClassWithDiscriminatorValueA::class, $aInstance);
		$this->assertEquals("a", $aInstance->value);
		$this->assertEquals(21, $aInstance->a);

		/** @var ClassWithDiscriminatorValueB $bInstance */
		$bInstance = ClassWithDiscriminatorMapMeta::fromArray(array("value" => "b", "a" => 21, "b" => 42));
		$this->assertInstanceOf(ClassWithDiscriminatorValueB::class, $bInstance);
		$this->assertEquals("b", $bInstance->value);
		$this->assertEquals(42, $bInstance->b);

		/** @var ClassWithDiscriminatorValueA $aTopInstance */
		$aTopInstance = ClassWithDiscriminatorMapMeta::fromArray(array("a" => array("a" => 63)), "top");
		$this->assertInstanceOf(ClassWithDiscriminatorValueA::class, $aTopInstance);
		$this->assertNull($aTopInstance->value);
		$this->assertEquals(63, $aTopInstance->a);

		/** @var ClassWithDiscriminatorValueB $bTopInstance */
		$bTopInstance = ClassWithDiscriminatorMapMeta::fromArray(array("b" => array("b" => 84)), "top");
		$this->assertInstanceOf(ClassWithDiscriminatorValueB::class, $bTopInstance);
		$this->assertNull($bTopInstance->value);
		$this->assertEquals(84, $bTopInstance->b);
	}

	public function testClassWithDiscriminatorMapToArray()
	{
		$aInstance = new ClassWithDiscriminatorValueA();
		$aInstance->a = 21;
		$aArray = ClassWithDiscriminatorMapMeta::toArray($aInstance);

		$this->assertArrayHasKey("value", $aArray);
		$this->assertEquals("a", $aArray["value"]);
		$this->assertArrayHasKey("a", $aArray);
		$this->assertEquals(21, $aArray["a"]);
		$this->assertArrayNotHasKey("b", $aArray);

		$bInstance = new ClassWithDiscriminatorValueB();
		$bInstance->b = 42;
		$bArray = ClassWithDiscriminatorMapMeta::toArray($bInstance);
		$this->assertArrayHasKey("value", $bArray);
		$this->assertEquals("b", $bArray["value"]);
		$this->assertArrayHasKey("b", $bArray);
		$this->assertEquals(42, $bArray["b"]);
		$this->assertArrayNotHasKey("a", $bArray);
	}

	public function testOverwriteClassWithPublicPropertyFromArray()
	{
		$instance = new ClassWithPublicProperty();
		$instance->property = "foobar";
		$this->assertEquals("foobar", $instance->property);

		ClassWithPublicPropertyMeta::fromArray(["property" => null], null, $instance);
		$this->assertNull($instance->property);
	}

	public function testOverwriteClassWithDatetimePropertyFromArray()
	{
		$now = new \DateTime();

		$instance = new ClassWithDatetimeProperty();
		$instance->datetime = $now;
		$this->assertEquals($now, $instance->datetime);

		ClassWithDatetimePropertyMeta::fromArray(["datetime" => null], null, $instance);
		$this->assertNull($instance->datetime);
	}

	public function testOverwriteClassWithArrayPropertyFromArray()
	{
		$instance = new ClassWithArrayProperty();
		$instance->array = ["foo" => ["bar" => "baz"]];
		$this->assertEquals(["foo" => ["bar" => "baz"]], $instance->array);

		ClassWithArrayPropertyMeta::fromArray(["array" => null], null, $instance);
		$this->assertNull($instance->array);
	}

	public function testClassWithRecursiveProperty()
	{
		$a = new ClassWithRecursiveProperty();
		$a->property = $a;

		$this->assertEquals(0, count(Stack::$objects));
		$this->assertEquals(["property" => null], ClassWithRecursivePropertyMeta::toArray($a));
		$this->assertEquals(0, count(Stack::$objects));

		$b = new ClassWithRecursiveProperty();
		$a->property = $b;
		$b->property = $a;

		$this->assertEquals(0, count(Stack::$objects));
		$this->assertEquals(["property" => ["property" => null]], ClassWithRecursivePropertyMeta::toArray($a));
		$this->assertEquals(0, count(Stack::$objects));

		try {
			$b->property = "wtf";

			$this->assertEquals(0, count(Stack::$objects));
			ClassWithRecursivePropertyMeta::toArray($a);
			$this->fail("An exception should be thrown.");

		} catch (\Exception $e) {
			$this->assertEquals(0, count(Stack::$objects));
			$this->assertEquals('You have to pass object of class Skrz\Meta\Fixtures\PHP\ClassWithRecursiveProperty.', $e->getMessage());
		}
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
			[
				"a" => "foo1",
				"b" => "foo2",
				"c" => "foo3",
				"d" => "foo4",
				"e" => "foo5",
				"f" => null,
				"g" => [],
			],
			ClassWithMorePropertiesMeta::toArray($instance)
		);

		$this->assertEquals(
			[
				"a" => "foo1",
			],
			ClassWithMorePropertiesMeta::toArray($instance, null, [
				"a" => true,
			])
		);

		$this->assertEquals(
			[
				"b" => "foo2",
				"c" => "foo3",
				"d" => "foo4",
			],
			ClassWithMorePropertiesMeta::toArray($instance, null, [
				"b" => true,
				"c" => true,
				"d" => true,
			])
		);

		$instance2 = new ClassWithMoreProperties();
		$instance2->a = "foo6";
		$instance->f = $instance2;

		$this->assertEquals(
			[
				"e" => "foo5",
				"f" => [
					"a" => "foo6",
					"b" => null,
				],
			],
			ClassWithMorePropertiesMeta::toArray($instance, null, [
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
			[
				"g" => [
					[
						"a" => "foo6",
					],
					[
						"a" => "foo7",
					]
				],
			],
			ClassWithMorePropertiesMeta::toArray($instance, null, [
				"g" => [
					"a" => true,
				],
			])
		);
	}

	public function testClassWithMorePropertiesFilteredByFieldsFromArray()
	{
		$instance = new ClassWithMoreProperties();
		$instance->a = "foo1";
		$instance->b = "foo2";
		$instance->c = "foo3";
		$instance->d = "foo4";
		$instance->e = "foo5";

		$this->assertEquals(
			[
				"a" => "foo1",
				"b" => "foo2",
				"c" => "foo3",
				"d" => "foo4",
				"e" => "foo5",
				"f" => null,
				"g" => [],
			],
			ClassWithMorePropertiesMeta::toArray($instance)
		);

		$this->assertEquals(
			[
				"a" => "foo1",
			],
			ClassWithMorePropertiesMeta::toArray($instance, null, Fields::fromArray([
				"a" => true,
			]))
		);

		$this->assertEquals(
			[
				"b" => "foo2",
				"c" => "foo3",
				"d" => "foo4",
			],
			ClassWithMorePropertiesMeta::toArray($instance, null, Fields::fromArray([
				"b" => true,
				"c" => true,
				"d" => true,
			]))
		);

		$instance2 = new ClassWithMoreProperties();
		$instance2->a = "foo6";
		$instance->f = $instance2;

		$this->assertEquals(
			[
				"e" => "foo5",
				"f" => [
					"a" => "foo6",
					"b" => null,
				],
			],
			ClassWithMorePropertiesMeta::toArray($instance, null, Fields::fromArray([
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
			[
				"g" => [
					[
						"a" => "foo6",
					],
					[
						"a" => "foo7",
					]
				],
			],
			ClassWithMorePropertiesMeta::toArray($instance, null, Fields::fromArray([
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
			[
				"a" => "foo1",
				"b" => "foo2",
				"c" => "foo3",
				"d" => "foo4",
				"e" => "foo5",
				"f" => null,
				"g" => [],
			],
			ClassWithMorePropertiesMeta::toArray($instance)
		);

		$this->assertEquals(
			[
				"a" => "foo1",
			],
			ClassWithMorePropertiesMeta::toArray($instance, null, Fields::fromString("a"))
		);

		$this->assertEquals(
			[
				"b" => "foo2",
				"c" => "foo3",
				"d" => "foo4",
			],
			ClassWithMorePropertiesMeta::toArray($instance, null, Fields::fromString("b,c,d"))
		);

		$instance2 = new ClassWithMoreProperties();
		$instance2->a = "foo6";
		$instance->f = $instance2;

		$this->assertEquals(
			[
				"e" => "foo5",
				"f" => [
					"a" => "foo6",
					"b" => null,
				],
			],
			ClassWithMorePropertiesMeta::toArray($instance, null, Fields::fromString("e,f{a,b}"))
		);

		$instance3 = new ClassWithMoreProperties();
		$instance3->a = "foo7";
		$instance->g = [$instance2, $instance3];

		$this->assertEquals(
			[
				"g" => [
					[
						"a" => "foo6",
					],
					[
						"a" => "foo7",
					]
				],
			],
			ClassWithMorePropertiesMeta::toArray($instance, null, Fields::fromString("g{a}"))
		);
	}

}
