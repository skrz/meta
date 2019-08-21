<?php
namespace Skrz\Meta\Protobuf;

use PHPUnit\Framework\TestCase;

class BinaryTest extends TestCase
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

	/**
	 * @dataProvider provideUint64
	 */
	public function testDecodeUint64($string, $number)
	{
		$offset = 0;
		$this->assertEquals($number, Binary::decodeUint64($string, $offset));
		$this->assertEquals(strlen($string), $offset);
	}

	/**
	 * @dataProvider provideUint64
	 */
	public function testEncodeUint64($string, $number)
	{
		$this->assertEquals($string, Binary::encodeUint64($number));
	}

	public function provideUint64()
	{
		return array(
			array("\x01\x00\x00\x00\x00\x00\x00\x00", 0x01),
			array("\x00\x01\x00\x00\x00\x00\x00\x00", 0x0100),
			array("\x00\x00\x01\x00\x00\x00\x00\x00", 0x010000),
			array("\x00\x00\x00\x01\x00\x00\x00\x00", 0x01000000),
			array("\x00\x00\x00\x00\x01\x00\x00\x00", 0x0100000000),
			array("\x00\x00\x00\x00\x00\x01\x00\x00", 0x010000000000),
			array("\x00\x00\x00\x00\x00\x00\x01\x00", 0x01000000000000),
			array("\x00\x00\x00\x00\x00\x00\x00\x01", 0x0100000000000000),
		);
	}

	/**
	 * @dataProvider provideInt64
	 */
	public function testDecodeInt64($string, $number)
	{
		$offset = 0;
		$this->assertEquals($number, Binary::decodeInt64($string, $offset));
		$this->assertEquals(strlen($string), $offset);
	}

	/**
	 * @dataProvider provideInt64
	 */
	public function testEncodeInt64($string, $number)
	{
		$this->assertEquals($string, Binary::encodeInt64($number));
	}

	public function provideInt64()
	{
		return array(
				array("\x01\x00\x00\x00\x00\x00\x00\x00", 0x01),
				array("\x00\x01\x00\x00\x00\x00\x00\x00", 0x0100),
				array("\x00\x00\x01\x00\x00\x00\x00\x00", 0x010000),
				array("\x00\x00\x00\x01\x00\x00\x00\x00", 0x01000000),
				array("\x00\x00\x00\x00\x01\x00\x00\x00", 0x0100000000),
				array("\x00\x00\x00\x00\x00\x01\x00\x00", 0x010000000000),
				array("\x00\x00\x00\x00\x00\x00\x01\x00", 0x01000000000000),
				array("\x00\x00\x00\x00\x00\x00\x00\x01", 0x0100000000000000),
				array("\xff\xff\xff\xff\xff\xff\xff\xff", -1),
				array("\xfe\xff\xff\xff\xff\xff\xff\xff", -2),
				array("\x60\x79\xfe\xff\xff\xff\xff\xff", -100000),
		);
	}

	/**
	 * @dataProvider provideUint32
	 */
	public function testDecodeUint32($string, $number)
	{
		$offset = 0;
		$this->assertEquals($number, Binary::decodeUint32($string, $offset));
		$this->assertEquals(strlen($string), $offset);
	}

	/**
	 * @dataProvider provideUint32
	 */
	public function testEncodeUint32($string, $number)
	{
		$this->assertEquals($string, Binary::encodeUint32($number));
	}

	public function provideUint32()
	{
		return array(
				array("\x01\x00\x00\x00", 0x01),
				array("\x00\x01\x00\x00", 0x0100),
				array("\x00\x00\x01\x00", 0x010000),
				array("\x00\x00\x00\x01", 0x01000000),
		);
	}

	/**
	 * @dataProvider provideInt32
	 */
	public function testDecodeInt32($string, $number)
	{
		$offset = 0;
		$this->assertEquals($number, Binary::decodeInt32($string, $offset));
		$this->assertEquals(strlen($string), $offset);
	}

	/**
	 * @dataProvider provideInt32
	 */
	public function testEncodeInt32($string, $number)
	{
		$this->assertEquals($string, Binary::encodeInt32($number));
	}

	public function provideInt32()
	{
		return array(
				array("\x01\x00\x00\x00", 0x01),
				array("\x00\x01\x00\x00", 0x0100),
				array("\x00\x00\x01\x00", 0x010000),
				array("\x00\x00\x00\x01", 0x01000000),
				array("\xff\xff\xff\xff", -1),
				array("\xfe\xff\xff\xff", -2),
				array("\x60\x79\xfe\xff", -100000),
		);
	}

}
