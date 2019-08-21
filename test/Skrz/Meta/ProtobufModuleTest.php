<?php
namespace Skrz\Meta;

use PHPUnit\Framework\TestCase;
use Skrz\Meta\Fixtures\Protobuf\ClassWithEmbeddedMessageProperty;
use Skrz\Meta\Fixtures\Protobuf\ClassWithFixed64Property;
use Skrz\Meta\Fixtures\Protobuf\ClassWithNoProperty;
use Skrz\Meta\Fixtures\Protobuf\ClassWithStringProperty;
use Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty;
use Skrz\Meta\Fixtures\Protobuf\Meta\ClassWithEmbeddedMessagePropertyMeta;
use Skrz\Meta\Fixtures\Protobuf\Meta\ClassWithFixed64PropertyMeta;
use Skrz\Meta\Fixtures\Protobuf\Meta\ClassWithNoPropertyMeta;
use Skrz\Meta\Fixtures\Protobuf\Meta\ClassWithStringPropertyMeta;
use Skrz\Meta\Fixtures\Protobuf\Meta\ClassWithVarintPropertyMeta;
use Skrz\Meta\Fixtures\Protobuf\ProtobufMetaSpec;
use Skrz\Meta\PHP\PhpMetaInterface;
use Skrz\Meta\Protobuf\Binary;
use Skrz\Meta\Protobuf\ProtobufMetaInterface;
use Skrz\Meta\Protobuf\WireTypeEnum;
use Symfony\Component\Finder\Finder;
use function array_map;
use function array_values;
use function iterator_to_array;

class ProtobufModuleTest extends TestCase
{

	public static function setUpBeforeClass()
	{
		$files = array_values(
			array_map(function (\SplFileInfo $file) {
				return $file->getRealPath();
			}, iterator_to_array(
				(new Finder())
					->in(__DIR__ . "/../../../gen-src/Skrz/Meta/Fixtures/Protobuf")
					->name("ClassWith*.php")
					->name("Embedded.php")
					->notName("*Meta.php")
					->files()
			))
		);

		$spec = new ProtobufMetaSpec();
		$spec->processFiles($files);
	}

	public function testSpecAnnotation()
	{
		$this->assertInstanceOf(ProtobufMetaInterface::class, ClassWithNoPropertyMeta::getInstance());
		$this->assertInstanceOf(PhpMetaInterface::class, ClassWithNoPropertyMeta::getInstance());
	}

	public function testClassWithNoPropertyFromEmptyProtobuf()
	{
		$instance = ClassWithNoPropertyMeta::fromProtobuf("");
		$this->assertInstanceOf(ClassWithNoProperty::class, $instance);
	}

	public function testClassWithVarintPropertyFromEmptyProtobuf()
	{
		$instance = ClassWithVarintPropertyMeta::fromProtobuf("");
		$this->assertInstanceOf(ClassWithVarintProperty::class, $instance);
		$this->assertNull($instance->getX());
	}

	public function testClassWithFixed64PropertyFromEmptyProtobuf()
	{
		$instance = ClassWithFixed64PropertyMeta::fromProtobuf("");
		$this->assertInstanceOf(ClassWithFixed64Property::class, $instance);
		$this->assertNull($instance->getX());
	}

	public function testClassWithStringPropertyFromEmptyProtobuf()
	{
		$instance = ClassWithStringPropertyMeta::fromProtobuf("");
		$this->assertInstanceOf(ClassWithStringProperty::class, $instance);
		$this->assertNull($instance->getX());
	}

	public function testClassWithEmbeddedMessagePropertyFromEmptyProtobuf()
	{
		$instance = ClassWithEmbeddedMessagePropertyMeta::fromProtobuf("");
		$this->assertInstanceOf(ClassWithEmbeddedMessageProperty::class, $instance);
		$this->assertNull($instance->getX());
	}

	public function testClassWithVarintPropertyFromProtobuf()
	{
		$instance = ClassWithVarintPropertyMeta::fromProtobuf(
			chr(1 << 3 | WireTypeEnum::toBinaryWireType(WireTypeEnum::VARINT)) .
			chr(6)
		);
		$this->assertInstanceOf(ClassWithVarintProperty::class, $instance);
		$this->assertEquals(6, $instance->getX());
	}

	public function testClassWithFixed64PropertyFromProtobuf()
	{
		$instance = ClassWithFixed64PropertyMeta::fromProtobuf(
			chr(1 << 3 | WireTypeEnum::toBinaryWireType(WireTypeEnum::FIXED64)) .
			Binary::encodeUint64(5)
		);
		$this->assertInstanceOf(ClassWithFixed64Property::class, $instance);
		$this->assertEquals(5, $instance->getX());
	}

	public function testClassWithStringPropertyFromProtobuf()
	{
		$s = "hello, world!";

		$instance = ClassWithStringPropertyMeta::fromProtobuf(
			chr(1 << 3 | WireTypeEnum::toBinaryWireType(WireTypeEnum::STRING)) .
			chr(strlen($s)) .
			$s
		);
		$this->assertInstanceOf(ClassWithStringProperty::class, $instance);
		$this->assertEquals($s, $instance->getX());
	}

	public function testClassWithEmbeddedMessagePropertyFromProtobuf()
	{
		$s = "hello from embedded!";
		$embedded =
			chr(1 << 3 | WireTypeEnum::toBinaryWireType(WireTypeEnum::STRING)) .
			chr(strlen($s)) .
			$s;
		$instance = ClassWithEmbeddedMessagePropertyMeta::fromProtobuf(
			chr(1 << 3 | WireTypeEnum::toBinaryWireType(WireTypeEnum::STRING)) .
			chr(strlen($embedded)) .
			$embedded

		);
		$this->assertInstanceOf(ClassWithEmbeddedMessageProperty::class, $instance);
		$this->assertNotNull($instance->getX());
		$this->assertEquals($s, $instance->getX()->getX());
	}

	public function testClassWithNoPropertyToEmptyProtobuf()
	{
		$this->assertEquals("", ClassWithNoPropertyMeta::toProtobuf(new ClassWithNoProperty()));
	}

	public function testClassWithVarintPropertyToEmptyProtobuf()
	{
		$this->assertEquals("", ClassWithVarintPropertyMeta::toProtobuf(new ClassWithVarintProperty()));
	}

	public function testClassWithFixed64PropertyToEmptyProtobuf()
	{
		$this->assertEquals("", ClassWithFixed64PropertyMeta::toProtobuf(new ClassWithFixed64Property()));
	}

	public function testClassWithStringPropertyToEmptyProtobuf()
	{
		$this->assertEquals("", ClassWithStringPropertyMeta::toProtobuf(new ClassWithStringProperty()));
	}

	public function testClassWithEmbeddedMessagePropertyToEmptyProtobuf()
	{
		$this->assertEquals("", ClassWithEmbeddedMessagePropertyMeta::toProtobuf(new ClassWithEmbeddedMessageProperty()));
	}

	public function testClassWithVarintPropertyToProtobuf()
	{
		$this->assertEquals(
			chr(1 << 3 | WireTypeEnum::toBinaryWireType(WireTypeEnum::VARINT)) . chr(10),
			ClassWithVarintPropertyMeta::toProtobuf(
				(new ClassWithVarintProperty())->setX(10)
			)
		);
	}

	public function testClassWithFixed64PropertyToProtobuf()
	{
		$this->assertEquals(
			chr(1 << 3 | WireTypeEnum::toBinaryWireType(WireTypeEnum::FIXED64)) . Binary::encodeUint64(45423),
			ClassWithFixed64PropertyMeta::toProtobuf(
				(new ClassWithFixed64Property())->setX(45423)
			)
		);
	}

	public function testClassWithStringPropertyToProtobuf()
	{
		$s = "mugala bugala mugala";

		$this->assertEquals(
			chr(1 << 3 | WireTypeEnum::toBinaryWireType(WireTypeEnum::STRING)) . chr(strlen($s)) . $s,
			ClassWithStringPropertyMeta::toProtobuf(
				(new ClassWithStringProperty())->setX($s)
			)
		);
	}

	public function testClassWithEmbeddedMessagePropertyToProtobuf()
	{
		$s = "the answer to life the universe and everything";
		$embedded = chr(1 << 3 | WireTypeEnum::toBinaryWireType(WireTypeEnum::STRING)) . chr(strlen($s)) . $s;

		$this->assertEquals(
			chr(1 << 3 | WireTypeEnum::toBinaryWireType(WireTypeEnum::STRING)) . chr(strlen($embedded)) . $embedded,
			ClassWithEmbeddedMessagePropertyMeta::toProtobuf(
				(new ClassWithEmbeddedMessageProperty())->setX(
					(new ClassWithEmbeddedMessageProperty\Embedded())->setX($s)
				)
			)
		);
	}

}
