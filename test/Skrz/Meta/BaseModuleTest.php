<?php
namespace Skrz\Meta;

use PHPUnit\Framework\TestCase;
use Skrz\Meta\Fixtures\Base\BaseMetaSpec;
use Skrz\Meta\Fixtures\Base\ClassToBeHashed;
use Skrz\Meta\Fixtures\Base\ClassWithNothing;
use Skrz\Meta\Fixtures\Base\ClassWithOneArgConstructor;
use Skrz\Meta\Fixtures\Base\ClassWithTwoArgConstructor;
use Skrz\Meta\Fixtures\Base\ClassWithZeroArgConstructor;
use Skrz\Meta\Fixtures\Base\Meta\ClassThatShouldBeIgnoredMeta;
use Skrz\Meta\Fixtures\Base\Meta\ClassToBeHashedMeta;
use Skrz\Meta\Fixtures\Base\Meta\ClassWithDefaultPropertiesMeta;
use Skrz\Meta\Fixtures\Base\Meta\ClassWithNothingMeta;
use Skrz\Meta\Fixtures\Base\Meta\ClassWithOneArgConstructorMeta;
use Skrz\Meta\Fixtures\Base\Meta\ClassWithTwoArgConstructorMeta;
use Skrz\Meta\Fixtures\Base\Meta\ClassWithZeroArgConstructorMeta;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class BaseModuleTest extends TestCase
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

	public function testClassToBeHashed()
	{
		$instance = new ClassToBeHashed();
		$this->assertEquals("d41d8cd98f00b204e9800998ecf8427e", ClassToBeHashedMeta::hash($instance));
		$this->assertEquals(base64_decode("1B2M2Y8AsgTpgAmY7PhCfg=="), ClassToBeHashedMeta::hash($instance, "md5", true));
		$this->assertEquals("da39a3ee5e6b4b0d3255bfef95601890afd80709", ClassToBeHashedMeta::hash($instance, "sha1"));
		$this->assertEquals(base64_decode("2jmj7l5rSw0yVb/vlWAYkK/YBwk="), ClassToBeHashedMeta::hash($instance, "sha1", true));

		$instance->a = "abc";
		$this->assertEquals("e638f7d51818758264fa897a551e5511", ClassToBeHashedMeta::hash($instance));

		$instance->b = 5;
		$instance->c = array(array(1.5, 1.7), array(345.1, 361.0));
		$instance2 = new ClassToBeHashed();
		$instance->d = $instance2;
		$instance->e = new \DateTime("2015-01-01", new \DateTimeZone("UTC"));

		$this->assertEquals("fb5cb1a12c56aa69911897f45577599e", ClassToBeHashedMeta::hash($instance));

		$instance->hash = ClassToBeHashedMeta::hash($instance);
		$this->assertEquals("fb5cb1a12c56aa69911897f45577599e", ClassToBeHashedMeta::hash($instance));

		$ctx = hash_init("md5");
		$this->assertEquals(null, ClassToBeHashedMeta::hash($instance, $ctx));
		$this->assertEquals("fb5cb1a12c56aa69911897f45577599e", hash_final($ctx));
	}

}
