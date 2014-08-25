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
		$this->assertFalse(class_exists(ClassThatShouldBeIgnoredMeta::class));
	}

	public function testClassWithNothing()
	{
		$this->assertInstanceOf(ClassWithNothingMeta::class, ClassWithNothingMeta::getInstance());
		$this->assertInstanceOf(ClassWithNothing::class, ClassWithNothingMeta::create());
		$this->assertInstanceOf(ClassWithNothing::class, ClassWithNothingMeta::create("no", "thing"));
	}

	public function testClassWithZeroArgConstructor()
	{
		$this->assertInstanceOf(ClassWithZeroArgConstructorMeta::class, ClassWithZeroArgConstructorMeta::getInstance());
		$this->assertInstanceOf(ClassWithZeroArgConstructor::class, ClassWithZeroArgConstructorMeta::create());
		$this->assertInstanceOf(ClassWithZeroArgConstructor::class, ClassWithZeroArgConstructorMeta::create("no", "thing"));
	}

	public function testClassWithOneArgConstructor()
	{
		$this->assertInstanceOf(ClassWithOneArgConstructorMeta::class, ClassWithOneArgConstructorMeta::getInstance());
		$instance = ClassWithOneArgConstructorMeta::create("something");
		$this->assertInstanceOf(ClassWithOneArgConstructor::class, $instance);
		$this->assertEquals("something", $instance->variable);
		ClassWithOneArgConstructorMeta::reset($instance);
		$this->assertNull($instance->variable);
		$this->assertInstanceOf(ClassWithOneArgConstructor::class, ClassWithOneArgConstructorMeta::create("something", "else"));
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
		$this->assertInstanceOf(ClassWithTwoArgConstructorMeta::class, ClassWithTwoArgConstructorMeta::getInstance());
		$instance = ClassWithTwoArgConstructorMeta::create("a", "b");
		$this->assertInstanceOf(ClassWithTwoArgConstructor::class, $instance);
		$this->assertEquals("a", $instance->variable1);
		$this->assertEquals("b", $instance->variable2);
		ClassWithTwoArgConstructorMeta::reset($instance);
		$this->assertNull($instance->variable1);
		$this->assertNull($instance->variable2);
		$this->assertInstanceOf(ClassWithTwoArgConstructor::class, ClassWithTwoArgConstructorMeta::create("a", "b", "c", "d"));
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
		$this->assertInstanceOf(ClassWithDefaultPropertiesMeta::class, ClassWithDefaultPropertiesMeta::getInstance());
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
