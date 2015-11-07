<?php
namespace Skrz\Meta\Protobuf;

class Binary
{

	/** @var bool */
	private static $isLittleEndian;

	/** @var bool */
	private static $native64BitPack;

	/**
	 * @return bool
	 */
	public static function isLittleEndian()
	{
		if (self::$isLittleEndian === null) {
			self::$isLittleEndian = unpack("S", "\x01\x00")[1] === 1;
		}
		return self::$isLittleEndian;
	}

	/**
	 * @return bool
	 */
	public static function isNative64BitPack()
	{
		if (self::$native64BitPack === null) {
			if (!defined("PHP_VERSION_ID")) {
				$version = explode(".", PHP_VERSION);
				define("PHP_VERSION_ID", ($version[0] * 10000 + $version[1] * 100 + $version[2]));
			}
			self::$native64BitPack = PHP_VERSION_ID >= 50603 && PHP_INT_SIZE === 8;
		}

		return self::$native64BitPack;
	}

	/**
	 * Swaps 32-bit integer endianness.
	 *
	 * @param string $s
	 * @return string
	 */
	public static function swapEndian32($s)
	{
		return $s[3] . $s[2] . $s[1] . $s[0];
	}

	/**
	 * Swaps 64-bit integer endianness.
	 *
	 * @param string $s
	 * @return string
	 */
	public static function swapEndian64($s)
	{
		return $s[7] . $s[6] . $s[5] . $s[4] . $s[3] . $s[2] . $s[1] . $s[0];
	}

	/**
	 * @param string $s
	 * @param int $offset
	 * @return int
	 */
	public static function decodeVarint($s, &$offset = 0)
	{
		$shift = 0;
		$value = 0;

		do {
			$i = ord($s[$offset++]);
			$value |= ($i & 0x7f) << $shift;
			$shift += 7;
		} while ($i & 0x80);

		return $value;
	}

	/**
	 * @param int $n
	 * @return string
	 */
	public static function encodeVarint($n)
	{
		$s = "";

		while ($n >= 0x80) {
			$s .= chr(($n & 0x7f) | 0x80);
			$n >>= 7;
		}

		$s .= chr($n);

		return $s;
	}

	/**
	 * @param string $s
	 * @param int $offset
	 * @return int
	 */
	public static function decodeZigzag($s, &$offset = 0)
	{
		$value = self::decodeVarint($s, $offset);
		// TODO: faster way?
		$result = round($value / 2);
		if ($value & 1) {
			$result = -$result;
		}
		return $result;
	}

	/**
	 * @param int $n
	 * @return string
	 */
	public static function encodeZigzag($n)
	{
		if ($n >= 0) {
			$s = self::encodeVarint($n * 2);
		} else {
			$s = self::encodeVarint(-$n * 2 - 1);
		}

		return $s;
	}

	/**
	 * @param string $s
	 * @param int $offset
	 * @return int
	 */
	public static function decodeInt32($s, &$offset = 0)
	{
		$s = substr($s, $offset, 4);
		$offset += 4;
		list(, $ret) = unpack("l", self::isLittleEndian() ? $s : self::swapEndian32($s));
		return $ret;
	}

	/**
	 * @param int $n
	 * @return string
	 */
	public static function encodeInt32($n)
	{
		$s = pack("l", $n);
		return self::isLittleEndian() ? $s : self::swapEndian32($s);
	}

	/**
	 * @param string $s
	 * @param int $offset
	 * @return int
	 */
	public static function decodeUint32($s, &$offset = 0)
	{
		$s = substr($s, $offset, 4);
		$offset += 4;
		list(, $ret) = unpack("V", $s);
		return $ret;
	}

	/**
	 * @param int $n
	 * @return string
	 */
	public static function encodeUint32($n)
	{
		return pack("V", $n);
	}

	/**
	 * @param string $s
	 * @param int $offset
	 * @return float
	 */
	public static function decodeFloat($s, &$offset = 0)
	{
		$s = substr($s, $offset, 4);
		$offset += 4;
		list(, $ret) = unpack("f", self::isLittleEndian() ? $s : self::swapEndian32($s));
		return $ret;
	}

	/**
	 * @param float $n
	 * @return string
	 */
	public static function encodeFloat($n)
	{
		$s = pack("f", $n);
		return self::isLittleEndian() ? $s : self::swapEndian32($s);
	}

	/**
	 * @param string $s
	 * @param int $offset
	 * @return int
	 */
	public static function decodeInt64($s, &$offset = 0)
	{
		$s = substr($s, $offset, 8);
		$offset += 8;
		if (self::isNative64BitPack()) {
			list(, $ret) = unpack("q", self::isLittleEndian() ? $s : self::swapEndian64($s));
		} else {
			$d = unpack("Vl/Vh", $s);
			$ret = $d["h"] << 32 | $d["l"];
		}
		return $ret;
	}

	/**
	 * @param int $n
	 * @return string
	 */
	public static function encodeInt64($n)
	{
		if (self::isNative64BitPack()) {
			$s = pack("q", $n);
			$s = self::isLittleEndian() ? $s : self::swapEndian64($s);
		} else {
			$s = pack("NN", ($n & 0xffffffff00000000) >> 32, $n & 0x00000000ffffffff);
			$s = self::isLittleEndian() ? self::swapEndian64($s) : $s; // intentionally reverse order
		}

		return $s;
	}

	/**
	 * @param string $s
	 * @param int $offset
	 * @return int
	 */
	public static function decodeUint64($s, &$offset = 0)
	{
		$s = substr($s, $offset, 8);
		$offset += 8;
		if (self::isNative64BitPack()) {
			list(, $ret) = unpack("P", $s);
		} else {
			$d = unpack("Vl/Vh", $s);
			$ret = $d["h"] << 32 | $d["l"];
		}
		return $ret;
	}

	/**
	 * @param int $n
	 * @return string
	 */
	public static function encodeUint64($n)
	{
		if (self::isNative64BitPack()) {
			$s = pack("P", $n);
		} else {
			$s = pack("NN", ($n & 0xffffffff00000000) >> 32, $n & 0x00000000ffffffff);
			$s = self::isLittleEndian() ? self::swapEndian64($s) : $s; // intentionally reverse order
		}

		return $s;
	}

	/**
	 * @param string $s
	 * @param int $offset
	 * @return float
	 */
	public static function decodeDouble($s, &$offset = 0)
	{
		$s = substr($s, $offset, 8);
		$offset += 8;
		list(, $ret) = unpack("d", self::$isLittleEndian ? $s : self::swapEndian64($s));
		return $ret;
	}

	/**
	 * @param float $n
	 * @return string
	 */
	public static function encodeDouble($n)
	{
		$s = pack("d", $n);
		return self::isLittleEndian() ? $s : self::swapEndian64($s);
	}

}
