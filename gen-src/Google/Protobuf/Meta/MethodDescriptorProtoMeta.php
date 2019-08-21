<?php
namespace Google\Protobuf\Meta;

use Closure;
use Google\Protobuf\MethodDescriptorProto;
use Skrz\Meta\MetaInterface;
use Skrz\Meta\Protobuf\Binary;
use Skrz\Meta\Protobuf\ProtobufException;
use Skrz\Meta\Protobuf\ProtobufMetaInterface;

/**
 * Meta class for \Google\Protobuf\MethodDescriptorProto
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class MethodDescriptorProtoMeta implements MetaInterface, ProtobufMetaInterface
{
	const NAME_PROTOBUF_FIELD = 1;
	const INPUT_TYPE_PROTOBUF_FIELD = 2;
	const OUTPUT_TYPE_PROTOBUF_FIELD = 3;
	const OPTIONS_PROTOBUF_FIELD = 4;
	const CLIENT_STREAMING_PROTOBUF_FIELD = 5;
	const SERVER_STREAMING_PROTOBUF_FIELD = 6;

	/** @var MethodDescriptorProtoMeta */
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
	 * @return MethodDescriptorProtoMeta
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			new self(); // self::$instance assigned in __construct
		}
		return self::$instance;
	}


	/**
	 * Creates new instance of \Google\Protobuf\MethodDescriptorProto
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return MethodDescriptorProto
	 */
	public static function create()
	{
		switch (func_num_args()) {
			case 0:
				return new MethodDescriptorProto();
			case 1:
				return new MethodDescriptorProto(func_get_arg(0));
			case 2:
				return new MethodDescriptorProto(func_get_arg(0), func_get_arg(1));
			case 3:
				return new MethodDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			case 4:
				return new MethodDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
			case 5:
				return new MethodDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			case 6:
				return new MethodDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
			case 7:
				return new MethodDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6));
			case 8:
				return new MethodDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6), func_get_arg(7));
			default:
				throw new \InvalidArgumentException('More than 8 arguments supplied, please be reasonable.');
		}
	}


	/**
	 * Resets properties of \Google\Protobuf\MethodDescriptorProto to default values
	 *
	 *
	 * @param MethodDescriptorProto $object
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	public static function reset($object)
	{
		if (!($object instanceof MethodDescriptorProto)) {
			throw new \InvalidArgumentException('You have to pass object of class Google\Protobuf\MethodDescriptorProto.');
		}

		if (self::$reset === null) {
			self::$reset = Closure::bind(static function ($object) {
				$object->name = null;
				$object->inputType = null;
				$object->outputType = null;
				$object->options = null;
				$object->clientStreaming = null;
				$object->serverStreaming = null;
			}, null, MethodDescriptorProto::class);
		}

		return (self::$reset)($object);
	}


	/**
	 * Computes hash of \Google\Protobuf\MethodDescriptorProto
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

				if (isset($object->inputType)) {
					hash_update($ctx, 'inputType');
					hash_update($ctx, (string)$object->inputType);
				}

				if (isset($object->outputType)) {
					hash_update($ctx, 'outputType');
					hash_update($ctx, (string)$object->outputType);
				}

				if (isset($object->options)) {
					hash_update($ctx, 'options');
					MethodOptionsMeta::hash($object->options, $ctx);
				}

				if (isset($object->clientStreaming)) {
					hash_update($ctx, 'clientStreaming');
					hash_update($ctx, (string)$object->clientStreaming);
				}

				if (isset($object->serverStreaming)) {
					hash_update($ctx, 'serverStreaming');
					hash_update($ctx, (string)$object->serverStreaming);
				}

				if (is_string($algoOrCtx)) {
					return hash_final($ctx, $raw);
				} else {
					return null;
				}
			}, null, MethodDescriptorProto::class);
		}

		return (self::$hash)($object, $algoOrCtx, $raw);
	}


	/**
	 * Creates \Google\Protobuf\MethodDescriptorProto object from serialized Protocol Buffers message.
	 *
	 * @param string $input
	 * @param MethodDescriptorProto $object
	 * @param int $start
	 * @param int $end
	 *
	 * @throws \Exception
	 *
	 * @return MethodDescriptorProto
	 */
	public static function fromProtobuf($input, $object = null, &$start = 0, $end = null)
	{
		if (self::$fromProtobuf === null) {
			self::$fromProtobuf = Closure::bind(static function ($input, $object, &$start, $end) {
				if ($object === null) {
					$object = new MethodDescriptorProto();
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
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->inputType = substr($input, $start, $length);
							$start += $length;
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
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
							$object->outputType = substr($input, $start, $length);
							$start += $length;
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 4:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->options = MethodOptionsMeta::fromProtobuf($input, null, $start, $start + $length);
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 5:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->clientStreaming = (bool)Binary::decodeVarint($input, $start);
							break;
						case 6:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->serverStreaming = (bool)Binary::decodeVarint($input, $start);
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
			}, null, MethodDescriptorProto::class);
		}

		return (self::$fromProtobuf)($input, $object, $start, $end);
	}


	/**
	 * Serialized \Google\Protobuf\MethodDescriptorProto to Protocol Buffers message.
	 *
	 * @param MethodDescriptorProto $object
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return string
	 */
	public static function toProtobuf($object, $filter = null)
	{
		if (self::$toProtobuf === null) {
			self::$toProtobuf = Closure::bind(static function (MethodDescriptorProto $object, $filter) {
				$output = '';

				if (isset($object->name) && ($filter === null || isset($filter['name']))) {
					$output .= "\x0a";
					$output .= Binary::encodeVarint(strlen($object->name));
					$output .= $object->name;
				}

				if (isset($object->inputType) && ($filter === null || isset($filter['inputType']))) {
					$output .= "\x12";
					$output .= Binary::encodeVarint(strlen($object->inputType));
					$output .= $object->inputType;
				}

				if (isset($object->outputType) && ($filter === null || isset($filter['outputType']))) {
					$output .= "\x1a";
					$output .= Binary::encodeVarint(strlen($object->outputType));
					$output .= $object->outputType;
				}

				if (isset($object->options) && ($filter === null || isset($filter['options']))) {
					$output .= "\x22";
					$buffer = MethodOptionsMeta::toProtobuf($object->options, $filter === null ? null : $filter['options']);
					$output .= Binary::encodeVarint(strlen($buffer));
					$output .= $buffer;
				}

				if (isset($object->clientStreaming) && ($filter === null || isset($filter['clientStreaming']))) {
					$output .= "\x28";
					$output .= Binary::encodeVarint((int)$object->clientStreaming);
				}

				if (isset($object->serverStreaming) && ($filter === null || isset($filter['serverStreaming']))) {
					$output .= "\x30";
					$output .= Binary::encodeVarint((int)$object->serverStreaming);
				}

				return $output;
			}, null, MethodDescriptorProto::class);
		}

		return (self::$toProtobuf)($object, $filter);
	}
}
