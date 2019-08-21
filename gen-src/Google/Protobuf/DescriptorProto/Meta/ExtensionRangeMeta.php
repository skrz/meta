<?php
namespace Google\Protobuf\DescriptorProto\Meta;

use Closure;
use Google\Protobuf\DescriptorProto\ExtensionRange;
use Skrz\Meta\MetaInterface;
use Skrz\Meta\Protobuf\Binary;
use Skrz\Meta\Protobuf\ProtobufException;
use Skrz\Meta\Protobuf\ProtobufMetaInterface;

/**
 * Meta class for \Google\Protobuf\DescriptorProto\ExtensionRange
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class ExtensionRangeMeta implements MetaInterface, ProtobufMetaInterface
{
	const START_PROTOBUF_FIELD = 1;
	const END_PROTOBUF_FIELD = 2;

	/** @var ExtensionRangeMeta */
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
	 * @return ExtensionRangeMeta
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			new self(); // self::$instance assigned in __construct
		}
		return self::$instance;
	}


	/**
	 * Creates new instance of \Google\Protobuf\DescriptorProto\ExtensionRange
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return ExtensionRange
	 */
	public static function create()
	{
		switch (func_num_args()) {
			case 0:
				return new ExtensionRange();
			case 1:
				return new ExtensionRange(func_get_arg(0));
			case 2:
				return new ExtensionRange(func_get_arg(0), func_get_arg(1));
			case 3:
				return new ExtensionRange(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			case 4:
				return new ExtensionRange(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
			case 5:
				return new ExtensionRange(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			case 6:
				return new ExtensionRange(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
			case 7:
				return new ExtensionRange(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6));
			case 8:
				return new ExtensionRange(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6), func_get_arg(7));
			default:
				throw new \InvalidArgumentException('More than 8 arguments supplied, please be reasonable.');
		}
	}


	/**
	 * Resets properties of \Google\Protobuf\DescriptorProto\ExtensionRange to default values
	 *
	 *
	 * @param ExtensionRange $object
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	public static function reset($object)
	{
		if (!($object instanceof ExtensionRange)) {
			throw new \InvalidArgumentException('You have to pass object of class Google\Protobuf\DescriptorProto\ExtensionRange.');
		}

		if (self::$reset === null) {
			self::$reset = Closure::bind(static function ($object) {
				$object->start = null;
				$object->end = null;
			}, null, ExtensionRange::class);
		}

		return (self::$reset)($object);
	}


	/**
	 * Computes hash of \Google\Protobuf\DescriptorProto\ExtensionRange
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

				if (isset($object->start)) {
					hash_update($ctx, 'start');
					hash_update($ctx, (string)$object->start);
				}

				if (isset($object->end)) {
					hash_update($ctx, 'end');
					hash_update($ctx, (string)$object->end);
				}

				if (is_string($algoOrCtx)) {
					return hash_final($ctx, $raw);
				} else {
					return null;
				}
			}, null, ExtensionRange::class);
		}

		return (self::$hash)($object, $algoOrCtx, $raw);
	}


	/**
	 * Creates \Google\Protobuf\DescriptorProto\ExtensionRange object from serialized Protocol Buffers message.
	 *
	 * @param string $input
	 * @param ExtensionRange $object
	 * @param int $start
	 * @param int $end
	 *
	 * @throws \Exception
	 *
	 * @return ExtensionRange
	 */
	public static function fromProtobuf($input, $object = null, &$start = 0, $end = null)
	{
		if (self::$fromProtobuf === null) {
			self::$fromProtobuf = Closure::bind(static function ($input, $object, &$start, $end) {
				if ($object === null) {
					$object = new ExtensionRange();
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
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->start = Binary::decodeVarint($input, $start);
							break;
						case 2:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->end = Binary::decodeVarint($input, $start);
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
			}, null, ExtensionRange::class);
		}

		return (self::$fromProtobuf)($input, $object, $start, $end);
	}


	/**
	 * Serialized \Google\Protobuf\DescriptorProto\ExtensionRange to Protocol Buffers message.
	 *
	 * @param ExtensionRange $object
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return string
	 */
	public static function toProtobuf($object, $filter = null)
	{
		if (self::$toProtobuf === null) {
			self::$toProtobuf = Closure::bind(static function (ExtensionRange $object, $filter) {
				$output = '';

				if (isset($object->start) && ($filter === null || isset($filter['start']))) {
					$output .= "\x08";
					$output .= Binary::encodeVarint($object->start);
				}

				if (isset($object->end) && ($filter === null || isset($filter['end']))) {
					$output .= "\x10";
					$output .= Binary::encodeVarint($object->end);
				}

				return $output;
			}, null, ExtensionRange::class);
		}

		return (self::$toProtobuf)($object, $filter);
	}
}
