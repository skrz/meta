<?php
namespace Skrz\Meta\Protobuf;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
final class WireTypeEnum
{

	private static $wireTypeMap = array(
		self::VARINT => 0,
		self::ZIGZAG => 0,
		self::FIXED64 => 1,
		self::STRING => 2,
		self::FIXED32 => 5,
	);

	const VARINT = "varint";

	const ZIGZAG = "zigzag";

	const FIXED64 = "fixed64";

	const STRING = "string";

	const FIXED32 = "fixed32";

	public static function toBinaryWireType($s)
	{
		return self::$wireTypeMap[$s];
	}

}
