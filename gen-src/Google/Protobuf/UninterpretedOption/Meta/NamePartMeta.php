<?php
namespace Google\Protobuf\UninterpretedOption\Meta;

use Closure;
use Google\Protobuf\UninterpretedOption\NamePart;
use Skrz\Meta\MetaInterface;
use Skrz\Meta\Protobuf\Binary;
use Skrz\Meta\Protobuf\ProtobufException;
use Skrz\Meta\Protobuf\ProtobufMetaInterface;

/**
 * Meta class for \Google\Protobuf\UninterpretedOption\NamePart
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class NamePartMeta implements MetaInterface, ProtobufMetaInterface
{
	const NAME_PART_PROTOBUF_FIELD = 1;
	const IS_EXTENSION_PROTOBUF_FIELD = 2;

	/** @var NamePartMeta */
	private static $instance;

	/** @var callable */
	private static $reset;

	/** @var callable */
	private static $hash;

	/** @var callable */
	private static $fromProtobuf;

	/** @var callable */
	private static $toProtobuf;


	/**
	 * Constructor
	 */
	private function __construct()
	{
		self::$instance = $this; // avoids cyclic dependency stack overflow
	}


	/**
	 * Returns instance of this meta class
	 *
	 * @return NamePartMeta
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			new self(); // self::$instance assigned in __construct
		}
		return self::$instance;
	}


	/**
	 * Creates new instance of \Google\Protobuf\UninterpretedOption\NamePart
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return NamePart
	 */
	public static function create()
	{
		switch (func_num_args()) {
			case 0:
				return new NamePart();
			case 1:
				return new NamePart(func_get_arg(0));
			case 2:
				return new NamePart(func_get_arg(0), func_get_arg(1));
			case 3:
				return new NamePart(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			case 4:
				return new NamePart(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
			case 5:
				return new NamePart(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			case 6:
				return new NamePart(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
			case 7:
				return new NamePart(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6));
			case 8:
				return new NamePart(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6), func_get_arg(7));
			default:
				throw new \InvalidArgumentException('More than 8 arguments supplied, please be reasonable.');
		}
	}


	/**
	 * Resets properties of \Google\Protobuf\UninterpretedOption\NamePart to default values
	 *
	 *
	 * @param NamePart $object
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	public static function reset($object)
	{
		if (!($object instanceof NamePart)) {
			throw new \InvalidArgumentException('You have to pass object of class Google\Protobuf\UninterpretedOption\NamePart.');
		}

		if (self::$reset === null) {
			self::$reset = Closure::bind(static function ($object) {
				$object->namePart = null;
				$object->isExtension = null;
			}, null, NamePart::class);
		}

		return (self::$reset)($object);
	}


	/**
	 * Computes hash of \Google\Protobuf\UninterpretedOption\NamePart
	 *
	 * @param object $object
	 * @param string|resource $algoOrCtx
	 * @param bool $raw
	 *
	 * @return string|void
	 */
	public static function hash($object, $algoOrCtx = 'md5', $raw = false)
	{
		if (self::$hash === null) {
			self::$hash = Closure::bind(static function ($object, $algoOrCtx, $raw) {
				if (is_string($algoOrCtx)) {
					$ctx = hash_init($algoOrCtx);
				} else {
					$ctx = $algoOrCtx;
				}

				if (isset($object->namePart)) {
					hash_update($ctx, 'namePart');
					hash_update($ctx, (string)$object->namePart);
				}

				if (isset($object->isExtension)) {
					hash_update($ctx, 'isExtension');
					hash_update($ctx, (string)$object->isExtension);
				}

				if (is_string($algoOrCtx)) {
					return hash_final($ctx, $raw);
				} else {
					return null;
				}
			}, null, NamePart::class);
		}

		return (self::$hash)($object, $algoOrCtx, $raw);
	}


	/**
	 * Creates \Google\Protobuf\UninterpretedOption\NamePart object from serialized Protocol Buffers message.
	 *
	 * @param string $input
	 * @param NamePart $object
	 * @param int $start
	 * @param int $end
	 *
	 * @throws \Exception
	 *
	 * @return NamePart
	 */
	public static function fromProtobuf($input, $object = null, &$start = 0, $end = null)
	{
		if (self::$fromProtobuf === null) {
			self::$fromProtobuf = Closure::bind(static function ($input, $object, &$start, $end) {
				if ($object === null) {
					$object = new NamePart();
				}

				if ($end === null) {
					$end = strlen($input);
				}

				while ($start < $end) {
					$tag = Binary::decodeVarint($input, $start);
					$wireType = $tag & 0x7;
					$number = $tag >> 3;
					switch ($number) {
						case 1:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->namePart = substr($input, $start, $length);
							$start += $length;
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 2:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->isExtension = (bool)Binary::decodeVarint($input, $start);
							break;
						default:
							switch ($wireType) {
								case 0:
									Binary::decodeVarint($input, $start);
									break;
								case 1:
									$start += 8;
									break;
								case 2:
									$start += Binary::decodeVarint($input, $start);
									break;
								case 5:
									$start += 4;
									break;
								default:
									throw new ProtobufException('Unexpected wire type ' . $wireType . '.', $number);
							}
					}
				}

				return $object;
			}, null, NamePart::class);
		}

		return (self::$fromProtobuf)($input, $object, $start, $end);
	}


	/**
	 * Serialized \Google\Protobuf\UninterpretedOption\NamePart to Protocol Buffers message.
	 *
	 * @param NamePart $object
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return string
	 */
	public static function toProtobuf($object, $filter = null)
	{
		if (self::$toProtobuf === null) {
			self::$toProtobuf = Closure::bind(static function (NamePart $object, $filter) {
				$output = '';

				if (isset($object->namePart) && ($filter === null || isset($filter['namePart']))) {
					$output .= "\x0a";
					$output .= Binary::encodeVarint(strlen($object->namePart));
					$output .= $object->namePart;
				}

				if (isset($object->isExtension) && ($filter === null || isset($filter['isExtension']))) {
					$output .= "\x10";
					$output .= Binary::encodeVarint((int)$object->isExtension);
				}

				return $output;
			}, null, NamePart::class);
		}

		return (self::$toProtobuf)($object, $filter);
	}
}
