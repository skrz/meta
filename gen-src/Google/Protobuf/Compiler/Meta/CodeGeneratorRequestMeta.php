<?php
namespace Google\Protobuf\Compiler\Meta;

use Closure;
use Google\Protobuf\Compiler\CodeGeneratorRequest;
use Google\Protobuf\Meta\FileDescriptorProtoMeta;
use Skrz\Meta\MetaInterface;
use Skrz\Meta\Protobuf\Binary;
use Skrz\Meta\Protobuf\ProtobufException;
use Skrz\Meta\Protobuf\ProtobufMetaInterface;

/**
 * Meta class for \Google\Protobuf\Compiler\CodeGeneratorRequest
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class CodeGeneratorRequestMeta implements MetaInterface, ProtobufMetaInterface
{
	const FILE_TO_GENERATE_PROTOBUF_FIELD = 1;
	const PARAMETER_PROTOBUF_FIELD = 2;
	const PROTO_FILE_PROTOBUF_FIELD = 15;

	/** @var CodeGeneratorRequestMeta */
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
	 * @return CodeGeneratorRequestMeta
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			new self(); // self::$instance assigned in __construct
		}
		return self::$instance;
	}


	/**
	 * Creates new instance of \Google\Protobuf\Compiler\CodeGeneratorRequest
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return CodeGeneratorRequest
	 */
	public static function create()
	{
		switch (func_num_args()) {
			case 0:
				return new CodeGeneratorRequest();
			case 1:
				return new CodeGeneratorRequest(func_get_arg(0));
			case 2:
				return new CodeGeneratorRequest(func_get_arg(0), func_get_arg(1));
			case 3:
				return new CodeGeneratorRequest(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			case 4:
				return new CodeGeneratorRequest(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
			case 5:
				return new CodeGeneratorRequest(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			case 6:
				return new CodeGeneratorRequest(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
			case 7:
				return new CodeGeneratorRequest(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6));
			case 8:
				return new CodeGeneratorRequest(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6), func_get_arg(7));
			default:
				throw new \InvalidArgumentException('More than 8 arguments supplied, please be reasonable.');
		}
	}


	/**
	 * Resets properties of \Google\Protobuf\Compiler\CodeGeneratorRequest to default values
	 *
	 *
	 * @param CodeGeneratorRequest $object
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	public static function reset($object)
	{
		if (!($object instanceof CodeGeneratorRequest)) {
			throw new \InvalidArgumentException('You have to pass object of class Google\Protobuf\Compiler\CodeGeneratorRequest.');
		}

		if (self::$reset === null) {
			self::$reset = Closure::bind(static function ($object) {
				$object->fileToGenerate = null;
				$object->parameter = null;
				$object->protoFile = null;
			}, null, CodeGeneratorRequest::class);
		}

		return (self::$reset)($object);
	}


	/**
	 * Computes hash of \Google\Protobuf\Compiler\CodeGeneratorRequest
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

				if (isset($object->fileToGenerate)) {
					hash_update($ctx, 'fileToGenerate');
					foreach ($object->fileToGenerate instanceof \Traversable ? $object->fileToGenerate : (array)$object->fileToGenerate as $v0) {
						hash_update($ctx, (string)$v0);
					}
				}

				if (isset($object->parameter)) {
					hash_update($ctx, 'parameter');
					hash_update($ctx, (string)$object->parameter);
				}

				if (isset($object->protoFile)) {
					hash_update($ctx, 'protoFile');
					foreach ($object->protoFile instanceof \Traversable ? $object->protoFile : (array)$object->protoFile as $v0) {
						FileDescriptorProtoMeta::hash($v0, $ctx);
					}
				}

				if (is_string($algoOrCtx)) {
					return hash_final($ctx, $raw);
				} else {
					return null;
				}
			}, null, CodeGeneratorRequest::class);
		}

		return (self::$hash)($object, $algoOrCtx, $raw);
	}


	/**
	 * Creates \Google\Protobuf\Compiler\CodeGeneratorRequest object from serialized Protocol Buffers message.
	 *
	 * @param string $input
	 * @param CodeGeneratorRequest $object
	 * @param int $start
	 * @param int $end
	 *
	 * @throws \Exception
	 *
	 * @return CodeGeneratorRequest
	 */
	public static function fromProtobuf($input, $object = null, &$start = 0, $end = null)
	{
		if (self::$fromProtobuf === null) {
			self::$fromProtobuf = Closure::bind(static function ($input, $object, &$start, $end) {
				if ($object === null) {
					$object = new CodeGeneratorRequest();
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
							if (!(isset($object->fileToGenerate) && is_array($object->fileToGenerate))) {
								$object->fileToGenerate = array();
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->fileToGenerate[] = substr($input, $start, $length);
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
							$object->parameter = substr($input, $start, $length);
							$start += $length;
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 15:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							if (!(isset($object->protoFile) && is_array($object->protoFile))) {
								$object->protoFile = array();
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->protoFile[] = FileDescriptorProtoMeta::fromProtobuf($input, null, $start, $start + $length);
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
			}, null, CodeGeneratorRequest::class);
		}

		return (self::$fromProtobuf)($input, $object, $start, $end);
	}


	/**
	 * Serialized \Google\Protobuf\Compiler\CodeGeneratorRequest to Protocol Buffers message.
	 *
	 * @param CodeGeneratorRequest $object
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return string
	 */
	public static function toProtobuf($object, $filter = null)
	{
		if (self::$toProtobuf === null) {
			self::$toProtobuf = Closure::bind(static function (CodeGeneratorRequest $object, $filter) {
				$output = '';

				if (isset($object->fileToGenerate) && ($filter === null || isset($filter['fileToGenerate']))) {
					foreach ($object->fileToGenerate instanceof \Traversable ? $object->fileToGenerate : (array)$object->fileToGenerate as $k => $v) {
						$output .= "\x0a";
						$output .= Binary::encodeVarint(strlen($v));
						$output .= $v;
					}
				}

				if (isset($object->parameter) && ($filter === null || isset($filter['parameter']))) {
					$output .= "\x12";
					$output .= Binary::encodeVarint(strlen($object->parameter));
					$output .= $object->parameter;
				}

				if (isset($object->protoFile) && ($filter === null || isset($filter['protoFile']))) {
					foreach ($object->protoFile instanceof \Traversable ? $object->protoFile : (array)$object->protoFile as $k => $v) {
						$output .= "\x7a";
						$buffer = FileDescriptorProtoMeta::toProtobuf($v, $filter === null ? null : $filter['protoFile']);
						$output .= Binary::encodeVarint(strlen($buffer));
						$output .= $buffer;
					}
				}

				return $output;
			}, null, CodeGeneratorRequest::class);
		}

		return (self::$toProtobuf)($object, $filter);
	}
}
