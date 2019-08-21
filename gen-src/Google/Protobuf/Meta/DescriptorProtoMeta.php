<?php
namespace Google\Protobuf\Meta;

use Closure;
use Google\Protobuf\DescriptorProto;
use Google\Protobuf\DescriptorProto\Meta\ExtensionRangeMeta;
use Google\Protobuf\DescriptorProto\Meta\ReservedRangeMeta;
use Skrz\Meta\MetaInterface;
use Skrz\Meta\Protobuf\Binary;
use Skrz\Meta\Protobuf\ProtobufException;
use Skrz\Meta\Protobuf\ProtobufMetaInterface;

/**
 * Meta class for \Google\Protobuf\DescriptorProto
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class DescriptorProtoMeta implements MetaInterface, ProtobufMetaInterface
{
	const NAME_PROTOBUF_FIELD = 1;
	const FIELD_PROTOBUF_FIELD = 2;
	const EXTENSION_PROTOBUF_FIELD = 6;
	const NESTED_TYPE_PROTOBUF_FIELD = 3;
	const ENUM_TYPE_PROTOBUF_FIELD = 4;
	const EXTENSION_RANGE_PROTOBUF_FIELD = 5;
	const ONEOF_DECL_PROTOBUF_FIELD = 8;
	const OPTIONS_PROTOBUF_FIELD = 7;
	const RESERVED_RANGE_PROTOBUF_FIELD = 9;
	const RESERVED_NAME_PROTOBUF_FIELD = 10;

	/** @var DescriptorProtoMeta */
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
	 * @return DescriptorProtoMeta
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			new self(); // self::$instance assigned in __construct
		}
		return self::$instance;
	}


	/**
	 * Creates new instance of \Google\Protobuf\DescriptorProto
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return DescriptorProto
	 */
	public static function create()
	{
		switch (func_num_args()) {
			case 0:
				return new DescriptorProto();
			case 1:
				return new DescriptorProto(func_get_arg(0));
			case 2:
				return new DescriptorProto(func_get_arg(0), func_get_arg(1));
			case 3:
				return new DescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			case 4:
				return new DescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
			case 5:
				return new DescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			case 6:
				return new DescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
			case 7:
				return new DescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6));
			case 8:
				return new DescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6), func_get_arg(7));
			default:
				throw new \InvalidArgumentException('More than 8 arguments supplied, please be reasonable.');
		}
	}


	/**
	 * Resets properties of \Google\Protobuf\DescriptorProto to default values
	 *
	 *
	 * @param DescriptorProto $object
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	public static function reset($object)
	{
		if (!($object instanceof DescriptorProto)) {
			throw new \InvalidArgumentException('You have to pass object of class Google\Protobuf\DescriptorProto.');
		}

		if (self::$reset === null) {
			self::$reset = Closure::bind(static function ($object) {
				$object->name = null;
				$object->field = null;
				$object->extension = null;
				$object->nestedType = null;
				$object->enumType = null;
				$object->extensionRange = null;
				$object->oneofDecl = null;
				$object->options = null;
				$object->reservedRange = null;
				$object->reservedName = null;
			}, null, DescriptorProto::class);
		}

		return (self::$reset)($object);
	}


	/**
	 * Computes hash of \Google\Protobuf\DescriptorProto
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

				if (isset($object->field)) {
					hash_update($ctx, 'field');
					foreach ($object->field instanceof \Traversable ? $object->field : (array)$object->field as $v0) {
						FieldDescriptorProtoMeta::hash($v0, $ctx);
					}
				}

				if (isset($object->extension)) {
					hash_update($ctx, 'extension');
					foreach ($object->extension instanceof \Traversable ? $object->extension : (array)$object->extension as $v0) {
						FieldDescriptorProtoMeta::hash($v0, $ctx);
					}
				}

				if (isset($object->nestedType)) {
					hash_update($ctx, 'nestedType');
					foreach ($object->nestedType instanceof \Traversable ? $object->nestedType : (array)$object->nestedType as $v0) {
						DescriptorProtoMeta::hash($v0, $ctx);
					}
				}

				if (isset($object->enumType)) {
					hash_update($ctx, 'enumType');
					foreach ($object->enumType instanceof \Traversable ? $object->enumType : (array)$object->enumType as $v0) {
						EnumDescriptorProtoMeta::hash($v0, $ctx);
					}
				}

				if (isset($object->extensionRange)) {
					hash_update($ctx, 'extensionRange');
					foreach ($object->extensionRange instanceof \Traversable ? $object->extensionRange : (array)$object->extensionRange as $v0) {
						ExtensionRangeMeta::hash($v0, $ctx);
					}
				}

				if (isset($object->oneofDecl)) {
					hash_update($ctx, 'oneofDecl');
					foreach ($object->oneofDecl instanceof \Traversable ? $object->oneofDecl : (array)$object->oneofDecl as $v0) {
						OneofDescriptorProtoMeta::hash($v0, $ctx);
					}
				}

				if (isset($object->options)) {
					hash_update($ctx, 'options');
					MessageOptionsMeta::hash($object->options, $ctx);
				}

				if (isset($object->reservedRange)) {
					hash_update($ctx, 'reservedRange');
					foreach ($object->reservedRange instanceof \Traversable ? $object->reservedRange : (array)$object->reservedRange as $v0) {
						ReservedRangeMeta::hash($v0, $ctx);
					}
				}

				if (isset($object->reservedName)) {
					hash_update($ctx, 'reservedName');
					foreach ($object->reservedName instanceof \Traversable ? $object->reservedName : (array)$object->reservedName as $v0) {
						hash_update($ctx, (string)$v0);
					}
				}

				if (is_string($algoOrCtx)) {
					return hash_final($ctx, $raw);
				} else {
					return null;
				}
			}, null, DescriptorProto::class);
		}

		return (self::$hash)($object, $algoOrCtx, $raw);
	}


	/**
	 * Creates \Google\Protobuf\DescriptorProto object from serialized Protocol Buffers message.
	 *
	 * @param string $input
	 * @param DescriptorProto $object
	 * @param int $start
	 * @param int $end
	 *
	 * @throws \Exception
	 *
	 * @return DescriptorProto
	 */
	public static function fromProtobuf($input, $object = null, &$start = 0, $end = null)
	{
		if (self::$fromProtobuf === null) {
			self::$fromProtobuf = Closure::bind(static function ($input, $object, &$start, $end) {
				if ($object === null) {
					$object = new DescriptorProto();
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
							if (!(isset($object->field) && is_array($object->field))) {
								$object->field = array();
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->field[] = FieldDescriptorProtoMeta::fromProtobuf($input, null, $start, $start + $length);
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 6:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							if (!(isset($object->extension) && is_array($object->extension))) {
								$object->extension = array();
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->extension[] = FieldDescriptorProtoMeta::fromProtobuf($input, null, $start, $start + $length);
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 3:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							if (!(isset($object->nestedType) && is_array($object->nestedType))) {
								$object->nestedType = array();
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->nestedType[] = DescriptorProtoMeta::fromProtobuf($input, null, $start, $start + $length);
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 4:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							if (!(isset($object->enumType) && is_array($object->enumType))) {
								$object->enumType = array();
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->enumType[] = EnumDescriptorProtoMeta::fromProtobuf($input, null, $start, $start + $length);
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 5:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							if (!(isset($object->extensionRange) && is_array($object->extensionRange))) {
								$object->extensionRange = array();
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->extensionRange[] = ExtensionRangeMeta::fromProtobuf($input, null, $start, $start + $length);
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 8:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							if (!(isset($object->oneofDecl) && is_array($object->oneofDecl))) {
								$object->oneofDecl = array();
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->oneofDecl[] = OneofDescriptorProtoMeta::fromProtobuf($input, null, $start, $start + $length);
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
							$object->options = MessageOptionsMeta::fromProtobuf($input, null, $start, $start + $length);
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 9:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							if (!(isset($object->reservedRange) && is_array($object->reservedRange))) {
								$object->reservedRange = array();
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->reservedRange[] = ReservedRangeMeta::fromProtobuf($input, null, $start, $start + $length);
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 10:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							if (!(isset($object->reservedName) && is_array($object->reservedName))) {
								$object->reservedName = array();
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->reservedName[] = substr($input, $start, $length);
							$start += $length;
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
			}, null, DescriptorProto::class);
		}

		return (self::$fromProtobuf)($input, $object, $start, $end);
	}


	/**
	 * Serialized \Google\Protobuf\DescriptorProto to Protocol Buffers message.
	 *
	 * @param DescriptorProto $object
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return string
	 */
	public static function toProtobuf($object, $filter = null)
	{
		if (self::$toProtobuf === null) {
			self::$toProtobuf = Closure::bind(static function (DescriptorProto $object, $filter) {
				$output = '';

				if (isset($object->name) && ($filter === null || isset($filter['name']))) {
					$output .= "\x0a";
					$output .= Binary::encodeVarint(strlen($object->name));
					$output .= $object->name;
				}

				if (isset($object->field) && ($filter === null || isset($filter['field']))) {
					foreach ($object->field instanceof \Traversable ? $object->field : (array)$object->field as $k => $v) {
						$output .= "\x12";
						$buffer = FieldDescriptorProtoMeta::toProtobuf($v, $filter === null ? null : $filter['field']);
						$output .= Binary::encodeVarint(strlen($buffer));
						$output .= $buffer;
					}
				}

				if (isset($object->extension) && ($filter === null || isset($filter['extension']))) {
					foreach ($object->extension instanceof \Traversable ? $object->extension : (array)$object->extension as $k => $v) {
						$output .= "\x32";
						$buffer = FieldDescriptorProtoMeta::toProtobuf($v, $filter === null ? null : $filter['extension']);
						$output .= Binary::encodeVarint(strlen($buffer));
						$output .= $buffer;
					}
				}

				if (isset($object->nestedType) && ($filter === null || isset($filter['nestedType']))) {
					foreach ($object->nestedType instanceof \Traversable ? $object->nestedType : (array)$object->nestedType as $k => $v) {
						$output .= "\x1a";
						$buffer = DescriptorProtoMeta::toProtobuf($v, $filter === null ? null : $filter['nestedType']);
						$output .= Binary::encodeVarint(strlen($buffer));
						$output .= $buffer;
					}
				}

				if (isset($object->enumType) && ($filter === null || isset($filter['enumType']))) {
					foreach ($object->enumType instanceof \Traversable ? $object->enumType : (array)$object->enumType as $k => $v) {
						$output .= "\x22";
						$buffer = EnumDescriptorProtoMeta::toProtobuf($v, $filter === null ? null : $filter['enumType']);
						$output .= Binary::encodeVarint(strlen($buffer));
						$output .= $buffer;
					}
				}

				if (isset($object->extensionRange) && ($filter === null || isset($filter['extensionRange']))) {
					foreach ($object->extensionRange instanceof \Traversable ? $object->extensionRange : (array)$object->extensionRange as $k => $v) {
						$output .= "\x2a";
						$buffer = ExtensionRangeMeta::toProtobuf($v, $filter === null ? null : $filter['extensionRange']);
						$output .= Binary::encodeVarint(strlen($buffer));
						$output .= $buffer;
					}
				}

				if (isset($object->oneofDecl) && ($filter === null || isset($filter['oneofDecl']))) {
					foreach ($object->oneofDecl instanceof \Traversable ? $object->oneofDecl : (array)$object->oneofDecl as $k => $v) {
						$output .= "\x42";
						$buffer = OneofDescriptorProtoMeta::toProtobuf($v, $filter === null ? null : $filter['oneofDecl']);
						$output .= Binary::encodeVarint(strlen($buffer));
						$output .= $buffer;
					}
				}

				if (isset($object->options) && ($filter === null || isset($filter['options']))) {
					$output .= "\x3a";
					$buffer = MessageOptionsMeta::toProtobuf($object->options, $filter === null ? null : $filter['options']);
					$output .= Binary::encodeVarint(strlen($buffer));
					$output .= $buffer;
				}

				if (isset($object->reservedRange) && ($filter === null || isset($filter['reservedRange']))) {
					foreach ($object->reservedRange instanceof \Traversable ? $object->reservedRange : (array)$object->reservedRange as $k => $v) {
						$output .= "\x4a";
						$buffer = ReservedRangeMeta::toProtobuf($v, $filter === null ? null : $filter['reservedRange']);
						$output .= Binary::encodeVarint(strlen($buffer));
						$output .= $buffer;
					}
				}

				if (isset($object->reservedName) && ($filter === null || isset($filter['reservedName']))) {
					foreach ($object->reservedName instanceof \Traversable ? $object->reservedName : (array)$object->reservedName as $k => $v) {
						$output .= "\x52";
						$output .= Binary::encodeVarint(strlen($v));
						$output .= $v;
					}
				}

				return $output;
			}, null, DescriptorProto::class);
		}

		return (self::$toProtobuf)($object, $filter);
	}
}
