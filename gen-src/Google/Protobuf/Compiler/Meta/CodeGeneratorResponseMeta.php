<?php
namespace Google\Protobuf\Compiler\Meta;

use Closure;
use Google\Protobuf\Compiler\CodeGeneratorResponse;
use Google\Protobuf\Compiler\CodeGeneratorResponse\Meta\FileMeta;
use Skrz\Meta\MetaInterface;
use Skrz\Meta\Protobuf\Binary;
use Skrz\Meta\Protobuf\ProtobufException;
use Skrz\Meta\Protobuf\ProtobufMetaInterface;

/**
 * Meta class for \Google\Protobuf\Compiler\CodeGeneratorResponse
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class CodeGeneratorResponseMeta implements MetaInterface, ProtobufMetaInterface
{
	const ERROR_PROTOBUF_FIELD = 1;
	const FILE_PROTOBUF_FIELD = 15;

	/** @var CodeGeneratorResponseMeta */
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
	 * @return CodeGeneratorResponseMeta
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			new self(); // self::$instance assigned in __construct
		}
		return self::$instance;
	}


	/**
	 * Creates new instance of \Google\Protobuf\Compiler\CodeGeneratorResponse
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return CodeGeneratorResponse
	 */
	public static function create()
	{
		switch (func_num_args()) {
			case 0:
				return new CodeGeneratorResponse();
			case 1:
				return new CodeGeneratorResponse(func_get_arg(0));
			case 2:
				return new CodeGeneratorResponse(func_get_arg(0), func_get_arg(1));
			case 3:
				return new CodeGeneratorResponse(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			case 4:
				return new CodeGeneratorResponse(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
			case 5:
				return new CodeGeneratorResponse(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			case 6:
				return new CodeGeneratorResponse(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
			case 7:
				return new CodeGeneratorResponse(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6));
			case 8:
				return new CodeGeneratorResponse(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6), func_get_arg(7));
			default:
				throw new \InvalidArgumentException('More than 8 arguments supplied, please be reasonable.');
		}
	}


	/**
	 * Resets properties of \Google\Protobuf\Compiler\CodeGeneratorResponse to default values
	 *
	 *
	 * @param CodeGeneratorResponse $object
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	public static function reset($object)
	{
		if (!($object instanceof CodeGeneratorResponse)) {
			throw new \InvalidArgumentException('You have to pass object of class Google\Protobuf\Compiler\CodeGeneratorResponse.');
		}

		if (self::$reset === null) {
			self::$reset = Closure::bind(static function ($object) {
				$object->error = null;
				$object->file = null;
			}, null, CodeGeneratorResponse::class);
		}

		return (self::$reset)($object);
	}


	/**
	 * Computes hash of \Google\Protobuf\Compiler\CodeGeneratorResponse
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

				if (isset($object->error)) {
					hash_update($ctx, 'error');
					hash_update($ctx, (string)$object->error);
				}

				if (isset($object->file)) {
					hash_update($ctx, 'file');
					foreach ($object->file instanceof \Traversable ? $object->file : (array)$object->file as $v0) {
						FileMeta::hash($v0, $ctx);
					}
				}

				if (is_string($algoOrCtx)) {
					return hash_final($ctx, $raw);
				} else {
					return null;
				}
			}, null, CodeGeneratorResponse::class);
		}

		return (self::$hash)($object, $algoOrCtx, $raw);
	}


	/**
	 * Creates \Google\Protobuf\Compiler\CodeGeneratorResponse object from serialized Protocol Buffers message.
	 *
	 * @param string $input
	 * @param CodeGeneratorResponse $object
	 * @param int $start
	 * @param int $end
	 *
	 * @throws \Exception
	 *
	 * @return CodeGeneratorResponse
	 */
	public static function fromProtobuf($input, $object = null, &$start = 0, $end = null)
	{
		if (self::$fromProtobuf === null) {
			self::$fromProtobuf = Closure::bind(static function ($input, $object, &$start, $end) {
				if ($object === null) {
					$object = new CodeGeneratorResponse();
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
							$object->error = substr($input, $start, $length);
							$start += $length;
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 15:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							if (!(isset($object->file) && is_array($object->file))) {
								$object->file = array();
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->file[] = FileMeta::fromProtobuf($input, null, $start, $start + $length);
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
			}, null, CodeGeneratorResponse::class);
		}

		return (self::$fromProtobuf)($input, $object, $start, $end);
	}


	/**
	 * Serialized \Google\Protobuf\Compiler\CodeGeneratorResponse to Protocol Buffers message.
	 *
	 * @param CodeGeneratorResponse $object
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return string
	 */
	public static function toProtobuf($object, $filter = null)
	{
		if (self::$toProtobuf === null) {
			self::$toProtobuf = Closure::bind(static function (CodeGeneratorResponse $object, $filter) {
				$output = '';

				if (isset($object->error) && ($filter === null || isset($filter['error']))) {
					$output .= "\x0a";
					$output .= Binary::encodeVarint(strlen($object->error));
					$output .= $object->error;
				}

				if (isset($object->file) && ($filter === null || isset($filter['file']))) {
					foreach ($object->file instanceof \Traversable ? $object->file : (array)$object->file as $k => $v) {
						$output .= "\x7a";
						$buffer = FileMeta::toProtobuf($v, $filter === null ? null : $filter['file']);
						$output .= Binary::encodeVarint(strlen($buffer));
						$output .= $buffer;
					}
				}

				return $output;
			}, null, CodeGeneratorResponse::class);
		}

		return (self::$toProtobuf)($object, $filter);
	}
}
