<?php
namespace Google\Protobuf\Meta;

use Closure;
use Google\Protobuf\FileDescriptorSet;
use Skrz\Meta\MetaInterface;
use Skrz\Meta\Protobuf\Binary;
use Skrz\Meta\Protobuf\ProtobufException;
use Skrz\Meta\Protobuf\ProtobufMetaInterface;

/**
 * Meta class for \Google\Protobuf\FileDescriptorSet
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class FileDescriptorSetMeta implements MetaInterface, ProtobufMetaInterface
{
	const FILE_PROTOBUF_FIELD = 1;

	/** @var FileDescriptorSetMeta */
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
	 * @return FileDescriptorSetMeta
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			new self(); // self::$instance assigned in __construct
		}
		return self::$instance;
	}


	/**
	 * Creates new instance of \Google\Protobuf\FileDescriptorSet
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return FileDescriptorSet
	 */
	public static function create()
	{
		switch (func_num_args()) {
			case 0:
				return new FileDescriptorSet();
			case 1:
				return new FileDescriptorSet(func_get_arg(0));
			case 2:
				return new FileDescriptorSet(func_get_arg(0), func_get_arg(1));
			case 3:
				return new FileDescriptorSet(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			case 4:
				return new FileDescriptorSet(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
			case 5:
				return new FileDescriptorSet(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			case 6:
				return new FileDescriptorSet(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
			case 7:
				return new FileDescriptorSet(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6));
			case 8:
				return new FileDescriptorSet(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6), func_get_arg(7));
			default:
				throw new \InvalidArgumentException('More than 8 arguments supplied, please be reasonable.');
		}
	}


	/**
	 * Resets properties of \Google\Protobuf\FileDescriptorSet to default values
	 *
	 *
	 * @param FileDescriptorSet $object
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	public static function reset($object)
	{
		if (!($object instanceof FileDescriptorSet)) {
			throw new \InvalidArgumentException('You have to pass object of class Google\Protobuf\FileDescriptorSet.');
		}

		if (self::$reset === null) {
			self::$reset = Closure::bind(static function ($object) {
				$object->file = null;
			}, null, FileDescriptorSet::class);
		}

		return (self::$reset)($object);
	}


	/**
	 * Computes hash of \Google\Protobuf\FileDescriptorSet
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

				if (isset($object->file)) {
					hash_update($ctx, 'file');
					foreach ($object->file instanceof \Traversable ? $object->file : (array)$object->file as $v0) {
						FileDescriptorProtoMeta::hash($v0, $ctx);
					}
				}

				if (is_string($algoOrCtx)) {
					return hash_final($ctx, $raw);
				} else {
					return null;
				}
			}, null, FileDescriptorSet::class);
		}

		return (self::$hash)($object, $algoOrCtx, $raw);
	}


	/**
	 * Creates \Google\Protobuf\FileDescriptorSet object from serialized Protocol Buffers message.
	 *
	 * @param string $input
	 * @param FileDescriptorSet $object
	 * @param int $start
	 * @param int $end
	 *
	 * @throws \Exception
	 *
	 * @return FileDescriptorSet
	 */
	public static function fromProtobuf($input, $object = null, &$start = 0, $end = null)
	{
		if (self::$fromProtobuf === null) {
			self::$fromProtobuf = Closure::bind(static function ($input, $object, &$start, $end) {
				if ($object === null) {
					$object = new FileDescriptorSet();
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
							if (!(isset($object->file) && is_array($object->file))) {
								$object->file = array();
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->file[] = FileDescriptorProtoMeta::fromProtobuf($input, null, $start, $start + $length);
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
			}, null, FileDescriptorSet::class);
		}

		return (self::$fromProtobuf)($input, $object, $start, $end);
	}


	/**
	 * Serialized \Google\Protobuf\FileDescriptorSet to Protocol Buffers message.
	 *
	 * @param FileDescriptorSet $object
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return string
	 */
	public static function toProtobuf($object, $filter = null)
	{
		if (self::$toProtobuf === null) {
			self::$toProtobuf = Closure::bind(static function (FileDescriptorSet $object, $filter) {
				$output = '';

				if (isset($object->file) && ($filter === null || isset($filter['file']))) {
					foreach ($object->file instanceof \Traversable ? $object->file : (array)$object->file as $k => $v) {
						$output .= "\x0a";
						$buffer = FileDescriptorProtoMeta::toProtobuf($v, $filter === null ? null : $filter['file']);
						$output .= Binary::encodeVarint(strlen($buffer));
						$output .= $buffer;
					}
				}

				return $output;
			}, null, FileDescriptorSet::class);
		}

		return (self::$toProtobuf)($object, $filter);
	}
}
