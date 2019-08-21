<?php
namespace Google\Protobuf\Meta;

use Closure;
use Google\Protobuf\FieldDescriptorProto;
use Skrz\Meta\MetaInterface;
use Skrz\Meta\Protobuf\Binary;
use Skrz\Meta\Protobuf\ProtobufException;
use Skrz\Meta\Protobuf\ProtobufMetaInterface;

/**
 * Meta class for \Google\Protobuf\FieldDescriptorProto
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class FieldDescriptorProtoMeta implements MetaInterface, ProtobufMetaInterface
{
	const NAME_PROTOBUF_FIELD = 1;
	const NUMBER_PROTOBUF_FIELD = 3;
	const LABEL_PROTOBUF_FIELD = 4;
	const TYPE_PROTOBUF_FIELD = 5;
	const TYPE_NAME_PROTOBUF_FIELD = 6;
	const EXTENDEE_PROTOBUF_FIELD = 2;
	const DEFAULT_VALUE_PROTOBUF_FIELD = 7;
	const ONEOF_INDEX_PROTOBUF_FIELD = 9;
	const JSON_NAME_PROTOBUF_FIELD = 10;
	const OPTIONS_PROTOBUF_FIELD = 8;

	/** @var FieldDescriptorProtoMeta */
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
	 * @return FieldDescriptorProtoMeta
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			new self(); // self::$instance assigned in __construct
		}
		return self::$instance;
	}


	/**
	 * Creates new instance of \Google\Protobuf\FieldDescriptorProto
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return FieldDescriptorProto
	 */
	public static function create()
	{
		switch (func_num_args()) {
			case 0:
				return new FieldDescriptorProto();
			case 1:
				return new FieldDescriptorProto(func_get_arg(0));
			case 2:
				return new FieldDescriptorProto(func_get_arg(0), func_get_arg(1));
			case 3:
				return new FieldDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			case 4:
				return new FieldDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
			case 5:
				return new FieldDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			case 6:
				return new FieldDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
			case 7:
				return new FieldDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6));
			case 8:
				return new FieldDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6), func_get_arg(7));
			default:
				throw new \InvalidArgumentException('More than 8 arguments supplied, please be reasonable.');
		}
	}


	/**
	 * Resets properties of \Google\Protobuf\FieldDescriptorProto to default values
	 *
	 *
	 * @param FieldDescriptorProto $object
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	public static function reset($object)
	{
		if (!($object instanceof FieldDescriptorProto)) {
			throw new \InvalidArgumentException('You have to pass object of class Google\Protobuf\FieldDescriptorProto.');
		}

		if (self::$reset === null) {
			self::$reset = Closure::bind(static function ($object) {
				$object->name = null;
				$object->number = null;
				$object->label = null;
				$object->type = null;
				$object->typeName = null;
				$object->extendee = null;
				$object->defaultValue = null;
				$object->oneofIndex = null;
				$object->jsonName = null;
				$object->options = null;
			}, null, FieldDescriptorProto::class);
		}

		return (self::$reset)($object);
	}


	/**
	 * Computes hash of \Google\Protobuf\FieldDescriptorProto
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

				if (isset($object->label)) {
					hash_update($ctx, 'label');
					hash_update($ctx, (string)$object->label);
				}

				if (isset($object->type)) {
					hash_update($ctx, 'type');
					hash_update($ctx, (string)$object->type);
				}

				if (isset($object->typeName)) {
					hash_update($ctx, 'typeName');
					hash_update($ctx, (string)$object->typeName);
				}

				if (isset($object->extendee)) {
					hash_update($ctx, 'extendee');
					hash_update($ctx, (string)$object->extendee);
				}

				if (isset($object->defaultValue)) {
					hash_update($ctx, 'defaultValue');
					hash_update($ctx, (string)$object->defaultValue);
				}

				if (isset($object->oneofIndex)) {
					hash_update($ctx, 'oneofIndex');
					hash_update($ctx, (string)$object->oneofIndex);
				}

				if (isset($object->jsonName)) {
					hash_update($ctx, 'jsonName');
					hash_update($ctx, (string)$object->jsonName);
				}

				if (isset($object->options)) {
					hash_update($ctx, 'options');
					FieldOptionsMeta::hash($object->options, $ctx);
				}

				if (is_string($algoOrCtx)) {
					return hash_final($ctx, $raw);
				} else {
					return null;
				}
			}, null, FieldDescriptorProto::class);
		}

		return (self::$hash)($object, $algoOrCtx, $raw);
	}


	/**
	 * Creates \Google\Protobuf\FieldDescriptorProto object from serialized Protocol Buffers message.
	 *
	 * @param string $input
	 * @param FieldDescriptorProto $object
	 * @param int $start
	 * @param int $end
	 *
	 * @throws \Exception
	 *
	 * @return FieldDescriptorProto
	 */
	public static function fromProtobuf($input, $object = null, &$start = 0, $end = null)
	{
		if (self::$fromProtobuf === null) {
			self::$fromProtobuf = Closure::bind(static function ($input, $object, &$start, $end) {
				if ($object === null) {
					$object = new FieldDescriptorProto();
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
						case 3:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->number = Binary::decodeVarint($input, $start);
							break;
						case 4:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->label = Binary::decodeVarint($input, $start);
							break;
						case 5:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->type = Binary::decodeVarint($input, $start);
							break;
						case 6:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->typeName = substr($input, $start, $length);
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
							$object->extendee = substr($input, $start, $length);
							$start += $length;
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 7:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->defaultValue = substr($input, $start, $length);
							$start += $length;
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 9:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->oneofIndex = Binary::decodeVarint($input, $start);
							break;
						case 10:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->jsonName = substr($input, $start, $length);
							$start += $length;
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 8:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->options = FieldOptionsMeta::fromProtobuf($input, null, $start, $start + $length);
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
			}, null, FieldDescriptorProto::class);
		}

		return (self::$fromProtobuf)($input, $object, $start, $end);
	}


	/**
	 * Serialized \Google\Protobuf\FieldDescriptorProto to Protocol Buffers message.
	 *
	 * @param FieldDescriptorProto $object
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return string
	 */
	public static function toProtobuf($object, $filter = null)
	{
		if (self::$toProtobuf === null) {
			self::$toProtobuf = Closure::bind(static function (FieldDescriptorProto $object, $filter) {
				$output = '';

				if (isset($object->name) && ($filter === null || isset($filter['name']))) {
					$output .= "\x0a";
					$output .= Binary::encodeVarint(strlen($object->name));
					$output .= $object->name;
				}

				if (isset($object->number) && ($filter === null || isset($filter['number']))) {
					$output .= "\x18";
					$output .= Binary::encodeVarint($object->number);
				}

				if (isset($object->label) && ($filter === null || isset($filter['label']))) {
					$output .= "\x20";
					$output .= Binary::encodeVarint($object->label);
				}

				if (isset($object->type) && ($filter === null || isset($filter['type']))) {
					$output .= "\x28";
					$output .= Binary::encodeVarint($object->type);
				}

				if (isset($object->typeName) && ($filter === null || isset($filter['typeName']))) {
					$output .= "\x32";
					$output .= Binary::encodeVarint(strlen($object->typeName));
					$output .= $object->typeName;
				}

				if (isset($object->extendee) && ($filter === null || isset($filter['extendee']))) {
					$output .= "\x12";
					$output .= Binary::encodeVarint(strlen($object->extendee));
					$output .= $object->extendee;
				}

				if (isset($object->defaultValue) && ($filter === null || isset($filter['defaultValue']))) {
					$output .= "\x3a";
					$output .= Binary::encodeVarint(strlen($object->defaultValue));
					$output .= $object->defaultValue;
				}

				if (isset($object->oneofIndex) && ($filter === null || isset($filter['oneofIndex']))) {
					$output .= "\x48";
					$output .= Binary::encodeVarint($object->oneofIndex);
				}

				if (isset($object->jsonName) && ($filter === null || isset($filter['jsonName']))) {
					$output .= "\x52";
					$output .= Binary::encodeVarint(strlen($object->jsonName));
					$output .= $object->jsonName;
				}

				if (isset($object->options) && ($filter === null || isset($filter['options']))) {
					$output .= "\x42";
					$buffer = FieldOptionsMeta::toProtobuf($object->options, $filter === null ? null : $filter['options']);
					$output .= Binary::encodeVarint(strlen($buffer));
					$output .= $buffer;
				}

				return $output;
			}, null, FieldDescriptorProto::class);
		}

		return (self::$toProtobuf)($object, $filter);
	}
}
