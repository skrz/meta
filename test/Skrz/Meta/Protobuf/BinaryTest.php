<?php
namespace Skrz\Meta\Protobuf;

class BinaryTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @dataProvider provideVarint
	 */
	public function testDecodeVarint($string, $number)
	{
		$offset = 0;
		$this->assertEquals($number, Binary::decodeVarint($string, $offset));
		$this->assertEquals(strlen($string), $offset);
	}

	/**
	 * @dataProvider provideVarint
	 */
	public function testEncodeVarint($string, $number)
	{
		$this->assertEquals($string, Binary::encodeVarint($number));
	}

	public function provideVarint()
	{
		return array(
			array("\x00", 0),
			array("\x01", 1),
			array("\x02", 2),
			array("\xAC\x02", 300),
		);
	}

	/**
	 * @dataProvider provideZigzag
	 */
	public function testDecodeZigzag($string, $number)
	{
		$offset = 0;
		$this->assertEquals($number, Binary::decodeZigzag($string, $offset));
		$this->assertEquals(strlen($string), $offset);
	}

	/**
	 * @dataProvider provideZigzag
	 */
	public function testEncodeZigzag($string, $number)
	{
		$this->assertEquals($string, Binary::encodeZigzag($number));
	}

	public function provideZigzag()
	{
		return array(
			array("\x00", 0),
			array("\x01", -1),
			array("\x02", 1),
			array("\x03", -2),
			array("\x04", 2),
			array("\x05", -3),
			array("\x06", 3),
		);
	}

}
