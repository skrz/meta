<?php
namespace Skrz\Meta;

use Skrz\Meta\Fixtures\Base\BaseMetaSpec;
use Skrz\Meta\Fixtures\Base\ClassWithNothing;
use Skrz\Meta\Fixtures\Base\ClassWithOneArgConstructor;
use Skrz\Meta\Fixtures\Base\ClassWithTwoArgConstructor;
use Skrz\Meta\Fixtures\Base\ClassWithZeroArgConstructor;
use Skrz\Meta\Fixtures\Base\Meta\ClassThatShouldBeIgnoredMeta;
use Skrz\Meta\Fixtures\Base\Meta\ClassWithDefaultPropertiesMeta;
use Skrz\Meta\Fixtures\Base\Meta\ClassWithNothingMeta;
use Skrz\Meta\Fixtures\Base\Meta\ClassWithOneArgConstructorMeta;
use Skrz\Meta\Fixtures\Base\Meta\ClassWithTwoArgConstructorMeta;
use Skrz\Meta\Fixtures\Base\Meta\ClassWithZeroArgConstructorMeta;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class BaseModuleTest extends \PHPUnit_Framework_TestCase
{

	public static function setUpBeforeClass()
	{
		$files = array_map(function (SplFileInfo $file) {
			return $file->getPathname();
		}, iterator_to_array(
			(new Finder())
				->in(__DIR__ . "/Fixtures/Base")
				->name("Class*.php")
				->notName("*Meta*")
				->files()
		));

		$spec = new BaseMetaSpec();
		$spec->processFiles($files);
	}

	public function testClassThatShouldBeIgnored()
	{
		// intentionally triggers autoload
		$this->assertFalse(class_exists("Skrz\\Meta\\Fixtures\\Base\\Meta\\ClassThatShouldBeIgnoredMeta"));
	}

	public function testClassWithNothing()
	{
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Base\\Meta\\ClassWithNothingMeta", ClassWithNothingMeta::getInstance());
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Base\\ClassWithNothing", ClassWithNothingMeta::create());
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Base\\ClassWithNothing", ClassWithNothingMeta::create("no", "thing"));
	}

	public function testClassWithZeroArgConstructor()
	{
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Base\\Meta\\ClassWithZeroArgConstructorMeta", ClassWithZeroArgConstructorMeta::getInstance());
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Base\\ClassWithZeroArgConstructor", ClassWithZeroArgConstructorMeta::create());
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Base\\ClassWithZeroArgConstructor", ClassWithZeroArgConstructorMeta::create("no", "thing"));
	}

	public function testClassWithOneArgConstructor()
	{
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Base\\Meta\\ClassWithOneArgConstructorMeta", ClassWithOneArgConstructorMeta::getInstance());
		$instance = ClassWithOneArgConstructorMeta::create("something");
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Base\\ClassWithOneArgConstructor", $instance);
		$this->assertEquals("something", $instance->variable);
		ClassWithOneArgConstructorMeta::reset($instance);
		$this->assertNull($instance->variable);
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Base\\ClassWithOneArgConstructor", ClassWithOneArgConstructorMeta::create("something", "else"));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testCreateClassWithOneArgConstructorThrowsZeroArg()
	{
		ClassWithOneArgConstructorMeta::create();
	}

	public function testClassWithTwoArgConstructor()
	{
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Base\\Meta\\ClassWithTwoArgConstructorMeta", ClassWithTwoArgConstructorMeta::getInstance());
		$instance = ClassWithTwoArgConstructorMeta::create("a", "b");
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Base\\ClassWithTwoArgConstructor", $instance);
		$this->assertEquals("a", $instance->variable1);
		$this->assertEquals("b", $instance->variable2);
		ClassWithTwoArgConstructorMeta::reset($instance);
		$this->assertNull($instance->variable1);
		$this->assertNull($instance->variable2);
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Base\\ClassWithTwoArgConstructor", ClassWithTwoArgConstructorMeta::create("a", "b", "c", "d"));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testCreateClassWithTwoArgConstructorThrowsZeroArg()
	{
		ClassWithTwoArgConstructorMeta::create();
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testCreateClassWithTwoArgConstructorThrowsOneArg()
	{
		ClassWithTwoArgConstructorMeta::create("a");
	}

	public function testClassWithDefaultProperties()
	{
		$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\Base\\Meta\\ClassWithDefaultPropertiesMeta", ClassWithDefaultPropertiesMeta::getInstance());
		$instance = ClassWithDefaultPropertiesMeta::create();
		$this->assertEquals("foo", $instance->defaultsToFoo);
		$this->assertEquals(42, $instance->defaultsTo42);
		$instance->defaultsToFoo = "bar";
		$instance->defaultsTo42 = 21;
		ClassWithDefaultPropertiesMeta::reset($instance);
		$this->assertEquals("foo", $instance->defaultsToFoo);
		$this->assertEquals(42, $instance->defaultsTo42);
	}

}
