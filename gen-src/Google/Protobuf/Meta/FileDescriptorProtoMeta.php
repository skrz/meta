<?php
namespace Google\Protobuf\Meta;

use Closure;
use Google\Protobuf\FileDescriptorProto;
use Skrz\Meta\MetaInterface;
use Skrz\Meta\Protobuf\Binary;
use Skrz\Meta\Protobuf\ProtobufException;
use Skrz\Meta\Protobuf\ProtobufMetaInterface;

/**
 * Meta class for \Google\Protobuf\FileDescriptorProto
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class FileDescriptorProtoMeta implements MetaInterface, ProtobufMetaInterface
{
	const NAME_PROTOBUF_FIELD = 1;
	const PACKAGE_PROTOBUF_FIELD = 2;
	const DEPENDENCY_PROTOBUF_FIELD = 3;
	const PUBLIC_DEPENDENCY_PROTOBUF_FIELD = 10;
	const WEAK_DEPENDENCY_PROTOBUF_FIELD = 11;
	const MESSAGE_TYPE_PROTOBUF_FIELD = 4;
	const ENUM_TYPE_PROTOBUF_FIELD = 5;
	const SERVICE_PROTOBUF_FIELD = 6;
	const EXTENSION_PROTOBUF_FIELD = 7;
	const OPTIONS_PROTOBUF_FIELD = 8;
	const SOURCE_CODE_INFO_PROTOBUF_FIELD = 9;
	const SYNTAX_PROTOBUF_FIELD = 12;

	/** @var FileDescriptorProtoMeta */
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
	 * @return FileDescriptorProtoMeta
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			new self(); // self::$instance assigned in __construct
		}
		return self::$instance;
	}


	/**
	 * Creates new instance of \Google\Protobuf\FileDescriptorProto
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return FileDescriptorProto
	 */
	public static function create()
	{
		switch (func_num_args()) {
			case 0:
				return new FileDescriptorProto();
			case 1:
				return new FileDescriptorProto(func_get_arg(0));
			case 2:
				return new FileDescriptorProto(func_get_arg(0), func_get_arg(1));
			case 3:
				return new FileDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			case 4:
				return new FileDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
			case 5:
				return new FileDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			case 6:
				return new FileDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
			case 7:
				return new FileDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6));
			case 8:
				return new FileDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6), func_get_arg(7));
			default:
				throw new \InvalidArgumentException('More than 8 arguments supplied, please be reasonable.');
		}
	}


	/**
	 * Resets properties of \Google\Protobuf\FileDescriptorProto to default values
	 *
	 *
	 * @param FileDescriptorProto $object
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	public static function reset($object)
	{
		if (!($object instanceof FileDescriptorProto)) {
			throw new \InvalidArgumentException('You have to pass object of class Google\Protobuf\FileDescriptorProto.');
		}

		if (self::$reset === null) {
			self::$reset = Closure::bind(static function ($object) {
				$object->name = null;
				$object->package = null;
				$object->dependency = null;
				$object->publicDependency = null;
				$object->weakDependency = null;
				$object->messageType = null;
				$object->enumType = null;
				$object->service = null;
				$object->extension = null;
				$object->options = null;
				$object->sourceCodeInfo = null;
				$object->syntax = null;
			}, null, FileDescriptorProto::class);
		}

		return (self::$reset)($object);
	}


	/**
	 * Computes hash of \Google\Protobuf\FileDescriptorProto
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

				if (isset($object->package)) {
					hash_update($ctx, 'package');
					hash_update($ctx, (string)$object->package);
				}

				if (isset($object->dependency)) {
					hash_update($ctx, 'dependency');
					foreach ($object->dependency instanceof \Traversable ? $object->dependency : (array)$object->dependency as $v0) {
						hash_update($ctx, (string)$v0);
					}
				}

				if (isset($object->publicDependency)) {
					hash_update($ctx, 'publicDependency');
					foreach ($object->publicDependency instanceof \Traversable ? $object->publicDependency : (array)$object->publicDependency as $v0) {
						hash_update($ctx, (string)$v0);
					}
				}

				if (isset($object->weakDependency)) {
					hash_update($ctx, 'weakDependency');
					foreach ($object->weakDependency instanceof \Traversable ? $object->weakDependency : (array)$object->weakDependency as $v0) {
						hash_update($ctx, (string)$v0);
					}
				}

				if (isset($object->messageType)) {
					hash_update($ctx, 'messageType');
					foreach ($object->messageType instanceof \Traversable ? $object->messageType : (array)$object->messageType as $v0) {
						DescriptorProtoMeta::hash($v0, $ctx);
					}
				}

				if (isset($object->enumType)) {
					hash_update($ctx, 'enumType');
					foreach ($object->enumType instanceof \Traversable ? $object->enumType : (array)$object->enumType as $v0) {
						EnumDescriptorProtoMeta::hash($v0, $ctx);
					}
				}

				if (isset($object->service)) {
					hash_update($ctx, 'service');
					foreach ($object->service instanceof \Traversable ? $object->service : (array)$object->service as $v0) {
						ServiceDescriptorProtoMeta::hash($v0, $ctx);
					}
				}

				if (isset($object->extension)) {
					hash_update($ctx, 'extension');
					foreach ($object->extension instanceof \Traversable ? $object->extension : (array)$object->extension as $v0) {
						FieldDescriptorProtoMeta::hash($v0, $ctx);
					}
				}

				if (isset($object->options)) {
					hash_update($ctx, 'options');
					FileOptionsMeta::hash($object->options, $ctx);
				}

				if (isset($object->sourceCodeInfo)) {
					hash_update($ctx, 'sourceCodeInfo');
					SourceCodeInfoMeta::hash($object->sourceCodeInfo, $ctx);
				}

				if (isset($object->syntax)) {
					hash_update($ctx, 'syntax');
					hash_update($ctx, (string)$object->syntax);
				}

				if (is_string($algoOrCtx)) {
					return hash_final($ctx, $raw);
				} else {
					return null;
				}
			}, null, FileDescriptorProto::class);
		}

		return (self::$hash)($object, $algoOrCtx, $raw);
	}


	/**
	 * Creates \Google\Protobuf\FileDescriptorProto object from serialized Protocol Buffers message.
	 *
	 * @param string $input
	 * @param FileDescriptorProto $object
	 * @param int $start
	 * @param int $end
	 *
	 * @throws \Exception
	 *
	 * @return FileDescriptorProto
	 */
	public static function fromProtobuf($input, $object = null, &$start = 0, $end = null)
	{
		if (self::$fromProtobuf === null) {
			self::$fromProtobuf = Closure::bind(static function ($input, $object, &$start, $end) {
				if ($object === null) {
					$object = new FileDescriptorProto();
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
							$object->package = substr($input, $start, $length);
							$start += $length;
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 3:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							if (!(isset($object->dependency) && is_array($object->dependency))) {
								$object->dependency = array();
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->dependency[] = substr($input, $start, $length);
							$start += $length;
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 10:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							if (!(isset($object->publicDependency) && is_array($object->publicDependency))) {
								$object->publicDependency = array();
							}
							$object->publicDependency[] = Binary::decodeVarint($input, $start);
							break;
						case 11:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							if (!(isset($object->weakDependency) && is_array($object->weakDependency))) {
								$object->weakDependency = array();
							}
							$object->weakDependency[] = Binary::decodeVarint($input, $start);
							break;
						case 4:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							if (!(isset($object->messageType) && is_array($object->messageType))) {
								$object->messageType = array();
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->messageType[] = DescriptorProtoMeta::fromProtobuf($input, null, $start, $start + $length);
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 5:
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
						case 6:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							if (!(isset($object->service) && is_array($object->service))) {
								$object->service = array();
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->service[] = ServiceDescriptorProtoMeta::fromProtobuf($input, null, $start, $start + $length);
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 7:
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
						case 8:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->options = FileOptionsMeta::fromProtobuf($input, null, $start, $start + $length);
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 9:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->sourceCodeInfo = SourceCodeInfoMeta::fromProtobuf($input, null, $start, $start + $length);
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 12:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->syntax = substr($input, $start, $length);
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
			}, null, FileDescriptorProto::class);
		}

		return (self::$fromProtobuf)($input, $object, $start, $end);
	}


	/**
	 * Serialized \Google\Protobuf\FileDescriptorProto to Protocol Buffers message.
	 *
	 * @param FileDescriptorProto $object
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return string
	 */
	public static function toProtobuf($object, $filter = null)
	{
		if (self::$toProtobuf === null) {
			self::$toProtobuf = Closure::bind(static function (FileDescriptorProto $object, $filter) {
				$output = '';

				if (isset($object->name) && ($filter === null || isset($filter['name']))) {
					$output .= "\x0a";
					$output .= Binary::encodeVarint(strlen($object->name));
					$output .= $object->name;
				}

				if (isset($object->package) && ($filter === null || isset($filter['package']))) {
					$output .= "\x12";
					$output .= Binary::encodeVarint(strlen($object->package));
					$output .= $object->package;
				}

				if (isset($object->dependency) && ($filter === null || isset($filter['dependency']))) {
					foreach ($object->dependency instanceof \Traversable ? $object->dependency : (array)$object->dependency as $k => $v) {
						$output .= "\x1a";
						$output .= Binary::encodeVarint(strlen($v));
						$output .= $v;
					}
				}

				if (isset($object->publicDependency) && ($filter === null || isset($filter['publicDependency']))) {
					foreach ($object->publicDependency instanceof \Traversable ? $object->publicDependency : (array)$object->publicDependency as $k => $v) {
						$output .= "\x50";
						$output .= Binary::encodeVarint($v);
					}
				}

				if (isset($object->weakDependency) && ($filter === null || isset($filter['weakDependency']))) {
					foreach ($object->weakDependency instanceof \Traversable ? $object->weakDependency : (array)$object->weakDependency as $k => $v) {
						$output .= "\x58";
						$output .= Binary::encodeVarint($v);
					}
				}

				if (isset($object->messageType) && ($filter === null || isset($filter['messageType']))) {
					foreach ($object->messageType instanceof \Traversable ? $object->messageType : (array)$object->messageType as $k => $v) {
						$output .= "\x22";
						$buffer = DescriptorProtoMeta::toProtobuf($v, $filter === null ? null : $filter['messageType']);
						$output .= Binary::encodeVarint(strlen($buffer));
						$output .= $buffer;
					}
				}

				if (isset($object->enumType) && ($filter === null || isset($filter['enumType']))) {
					foreach ($object->enumType instanceof \Traversable ? $object->enumType : (array)$object->enumType as $k => $v) {
						$output .= "\x2a";
						$buffer = EnumDescriptorProtoMeta::toProtobuf($v, $filter === null ? null : $filter['enumType']);
						$output .= Binary::encodeVarint(strlen($buffer));
						$output .= $buffer;
					}
				}

				if (isset($object->service) && ($filter === null || isset($filter['service']))) {
					foreach ($object->service instanceof \Traversable ? $object->service : (array)$object->service as $k => $v) {
						$output .= "\x32";
						$buffer = ServiceDescriptorProtoMeta::toProtobuf($v, $filter === null ? null : $filter['service']);
						$output .= Binary::encodeVarint(strlen($buffer));
						$output .= $buffer;
					}
				}

				if (isset($object->extension) && ($filter === null || isset($filter['extension']))) {
					foreach ($object->extension instanceof \Traversable ? $object->extension : (array)$object->extension as $k => $v) {
						$output .= "\x3a";
						$buffer = FieldDescriptorProtoMeta::toProtobuf($v, $filter === null ? null : $filter['extension']);
						$output .= Binary::encodeVarint(strlen($buffer));
						$output .= $buffer;
					}
				}

				if (isset($object->options) && ($filter === null || isset($filter['options']))) {
					$output .= "\x42";
					$buffer = FileOptionsMeta::toProtobuf($object->options, $filter === null ? null : $filter['options']);
					$output .= Binary::encodeVarint(strlen($buffer));
					$output .= $buffer;
				}

				if (isset($object->sourceCodeInfo) && ($filter === null || isset($filter['sourceCodeInfo']))) {
					$output .= "\x4a";
					$buffer = SourceCodeInfoMeta::toProtobuf($object->sourceCodeInfo, $filter === null ? null : $filter['sourceCodeInfo']);
					$output .= Binary::encodeVarint(strlen($buffer));
					$output .= $buffer;
				}

				if (isset($object->syntax) && ($filter === null || isset($filter['syntax']))) {
					$output .= "\x62";
					$output .= Binary::encodeVarint(strlen($object->syntax));
					$output .= $object->syntax;
				}

				return $output;
			}, null, FileDescriptorProto::class);
		}

		return (self::$toProtobuf)($object, $filter);
	}
}
