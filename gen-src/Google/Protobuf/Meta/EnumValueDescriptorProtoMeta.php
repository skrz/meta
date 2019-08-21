<?php
namespace Google\Protobuf\Meta;

use Closure;
use Google\Protobuf\EnumValueDescriptorProto;
use Skrz\Meta\MetaInterface;
use Skrz\Meta\Protobuf\Binary;
use Skrz\Meta\Protobuf\ProtobufException;
use Skrz\Meta\Protobuf\ProtobufMetaInterface;

/**
 * Meta class for \Google\Protobuf\EnumValueDescriptorProto
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class EnumValueDescriptorProtoMeta implements MetaInterface, ProtobufMetaInterface
{
	const NAME_PROTOBUF_FIELD = 1;
	const NUMBER_PROTOBUF_FIELD = 2;
	const OPTIONS_PROTOBUF_FIELD = 3;

	/** @var EnumValueDescriptorProtoMeta */
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
	 * @return EnumValueDescriptorProtoMeta
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			new self(); // self::$instance assigned in __construct
		}
		return self::$instance;
	}


	/**
	 * Creates new instance of \Google\Protobuf\EnumValueDescriptorProto
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return EnumValueDescriptorProto
	 */
	public static function create()
	{
		switch (func_num_args()) {
			case 0:
				return new EnumValueDescriptorProto();
			case 1:
				return new EnumValueDescriptorProto(func_get_arg(0));
			case 2:
				return new EnumValueDescriptorProto(func_get_arg(0), func_get_arg(1));
			case 3:
				return new EnumValueDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			case 4:
				return new EnumValueDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
			case 5:
				return new EnumValueDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			case 6:
				return new EnumValueDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
			case 7:
				return new EnumValueDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6));
			case 8:
				return new EnumValueDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6), func_get_arg(7));
			default:
				throw new \InvalidArgumentException('More than 8 arguments supplied, please be reasonable.');
		}
	}


	/**
	 * Resets properties of \Google\Protobuf\EnumValueDescriptorProto to default values
	 *
	 *
	 * @param EnumValueDescriptorProto $object
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	public static function reset($object)
	{
		if (!($object instanceof EnumValueDescriptorProto)) {
			throw new \InvalidArgumentException('You have to pass object of class Google\Protobuf\EnumValueDescriptorProto.');
		}

		if (self::$reset === null) {
			self::$reset = Closure::bind(static function ($object) {
				$object->name = null;
				$object->number = null;
				$object->options = null;
			}, null, EnumValueDescriptorProto::class);
		}

		return (self::$reset)($object);
	}


	/**
	 * Computes hash of \Google\Protobuf\EnumValueDescriptorProto
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

				if (isset($object->name)) {
					hash_update($ctx, 'name');
					hash_update($ctx, (string)$object->name);
				}

				if (isset($object->number)) {
					hash_update($ctx, 'number');
					hash_update($ctx, (string)$object->number);
				}

				if (isset($object->options)) {
					hash_update($ctx, 'options');
					EnumValueOptionsMeta::hash($object->options, $ctx);
				}

				if (is_string($algoOrCtx)) {
					return hash_final($ctx, $raw);
				} else {
					return null;
				}
			}, null, EnumValueDescriptorProto::class);
		}

		return (self::$hash)($object, $algoOrCtx, $raw);
	}


	/**
	 * Creates \Google\Protobuf\EnumValueDescriptorProto object from serialized Protocol Buffers message.
	 *
	 * @param string $input
	 * @param EnumValueDescriptorProto $object
	 * @param int $start
	 * @param int $end
	 *
	 * @throws \Exception
	 *
	 * @return EnumValueDescriptorProto
	 */
	public static function fromProtobuf($input, $object = null, &$start = 0, $end = null)
	{
		if (self::$fromProtobuf === null) {
			self::$fromProtobuf = Closure::bind(static function ($input, $object, &$start, $end) {
				if ($object === null) {
					$object = new EnumValueDescriptorProto();
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
							$object->name = substr($input, $start, $length);
							$start += $length;
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 2:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->number = Binary::decodeVarint($input, $start);
							break;
						case 3:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->options = EnumValueOptionsMeta::fromProtobuf($input, null, $start, $start + $length);
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
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
			}, null, EnumValueDescriptorProto::class);
		}

		return (self::$fromProtobuf)($input, $object, $start, $end);
	}


	/**
	 * Serialized \Google\Protobuf\EnumValueDescriptorProto to Protocol Buffers message.
	 *
	 * @param EnumValueDescriptorProto $object
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return string
	 */
	public static function toProtobuf($object, $filter = null)
	{
		if (self::$toProtobuf === null) {
			self::$toProtobuf = Closure::bind(static function (EnumValueDescriptorProto $object, $filter) {
				$output = '';

				if (isset($object->name) && ($filter === null || isset($filter['name']))) {
					$output .= "\x0a";
					$output .= Binary::encodeVarint(strlen($object->name));
					$output .= $object->name;
				}

				if (isset($object->number) && ($filter === null || isset($filter['number']))) {
					$output .= "\x10";
					$output .= Binary::encodeVarint($object->number);
				}

				if (isset($object->options) && ($filter === null || isset($filter['options']))) {
					$output .= "\x1a";
					$buffer = EnumValueOptionsMeta::toProtobuf($object->options, $filter === null ? null : $filter['options']);
					$output .= Binary::encodeVarint(strlen($buffer));
					$output .= $buffer;
				}

				return $output;
			}, null, EnumValueDescriptorProto::class);
		}

		return (self::$toProtobuf)($object, $filter);
	}
}
