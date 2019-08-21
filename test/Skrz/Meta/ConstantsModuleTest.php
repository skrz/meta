<?php
namespace Skrz\Meta;

use PHPUnit\Framework\TestCase;
use Skrz\Meta\Fixtures\Constants\ClassWithProperties;
use Skrz\Meta\Fixtures\Constants\ConstantsMetaSpec;
use Skrz\Meta\Fixtures\Constants\Meta\ClassWithPropertiesMeta;
use Symfony\Component\Finder\Finder;

class ConstantsModuleTest extends TestCase
{

	public static function setUpBeforeClass()
	{
		$files = array_map(function (\SplFileInfo $file) {
			return $file->getPathname();
		}, iterator_to_array(
			(new Finder())
				->in(__DIR__ . "/Fixtures/Constants")
				->name("Class*.php")
				->notName("*Meta*")
				->files()
		));

		$spec = new ConstantsMetaSpec();
		$spec->processFiles($files);
	}

	public function testClassNameConstant()
	{
		$this->assertEquals(ClassWithProperties::class, ClassWithPropertiesMeta::CLASS_NAME);
	}

	public function testShortNameConstant()
	{
		$this->assertEquals("ClassWithProperties", ClassWithPropertiesMeta::SHORT_NAME);
	}

	public function testEntityNameConstant()
	{
		$this->assertEquals("classWithProperties", ClassWithPropertiesMeta::ENTITY_NAME);
	}

	public function testPropertyConstants()
	{
		$this->assertEquals("property", ClassWithPropertiesMeta::PROPERTY);
		$this->assertEquals("anotherProperty", ClassWithPropertiesMeta::ANOTHER_PROPERTY);
		$this->assertEquals("property_with_underscores", ClassWithPropertiesMeta::PROPERTY_WITH_UNDERSCORES);
	}

}
