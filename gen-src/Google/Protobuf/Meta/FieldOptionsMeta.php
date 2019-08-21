<?php
namespace Google\Protobuf\Meta;

use Closure;
use Google\Protobuf\FieldOptions;
use Skrz\Meta\MetaInterface;
use Skrz\Meta\Protobuf\Binary;
use Skrz\Meta\Protobuf\ProtobufException;
use Skrz\Meta\Protobuf\ProtobufMetaInterface;

/**
 * Meta class for \Google\Protobuf\FieldOptions
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class FieldOptionsMeta implements MetaInterface, ProtobufMetaInterface
{
	const CTYPE_PROTOBUF_FIELD = 1;
	const PACKED_PROTOBUF_FIELD = 2;
	const JSTYPE_PROTOBUF_FIELD = 6;
	const LAZY_PROTOBUF_FIELD = 5;
	const DEPRECATED_PROTOBUF_FIELD = 3;
	const WEAK_PROTOBUF_FIELD = 10;
	const UNINTERPRETED_OPTION_PROTOBUF_FIELD = 999;

	/** @var FieldOptionsMeta */
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
	 * @return FieldOptionsMeta
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			new self(); // self::$instance assigned in __construct
		}
		return self::$instance;
	}


	/**
	 * Creates new instance of \Google\Protobuf\FieldOptions
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return FieldOptions
	 */
	public static function create()
	{
		switch (func_num_args()) {
			case 0:
				return new FieldOptions();
			case 1:
				return new FieldOptions(func_get_arg(0));
			case 2:
				return new FieldOptions(func_get_arg(0), func_get_arg(1));
			case 3:
				return new FieldOptions(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			case 4:
				return new FieldOptions(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
			case 5:
				return new FieldOptions(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			case 6:
				return new FieldOptions(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
			case 7:
				return new FieldOptions(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6));
			case 8:
				return new FieldOptions(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6), func_get_arg(7));
			default:
				throw new \InvalidArgumentException('More than 8 arguments supplied, please be reasonable.');
		}
	}


	/**
	 * Resets properties of \Google\Protobuf\FieldOptions to default values
	 *
	 *
	 * @param FieldOptions $object
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	public static function reset($object)
	{
		if (!($object instanceof FieldOptions)) {
			throw new \InvalidArgumentException('You have to pass object of class Google\Protobuf\FieldOptions.');
		}

		if (self::$reset === null) {
			self::$reset = Closure::bind(static function ($object) {
				$object->ctype = null;
				$object->packed = null;
				$object->jstype = null;
				$object->lazy = null;
				$object->deprecated = null;
				$object->weak = null;
				$object->uninterpretedOption = null;
			}, null, FieldOptions::class);
		}

		return (self::$reset)($object);
	}


	/**
	 * Computes hash of \Google\Protobuf\FieldOptions
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

				if (isset($object->ctype)) {
					hash_update($ctx, 'ctype');
					hash_update($ctx, (string)$object->ctype);
				}

				if (isset($object->packed)) {
					hash_update($ctx, 'packed');
					hash_update($ctx, (string)$object->packed);
				}

				if (isset($object->jstype)) {
					hash_update($ctx, 'jstype');
					hash_update($ctx, (string)$object->jstype);
				}

				if (isset($object->lazy)) {
					hash_update($ctx, 'lazy');
					hash_update($ctx, (string)$object->lazy);
				}

				if (isset($object->deprecated)) {
					hash_update($ctx, 'deprecated');
					hash_update($ctx, (string)$object->deprecated);
				}

				if (isset($object->weak)) {
					hash_update($ctx, 'weak');
					hash_update($ctx, (string)$object->weak);
				}

				if (isset($object->uninterpretedOption)) {
					hash_update($ctx, 'uninterpretedOption');
					foreach ($object->uninterpretedOption instanceof \Traversable ? $object->uninterpretedOption : (array)$object->uninterpretedOption as $v0) {
						UninterpretedOptionMeta::hash($v0, $ctx);
					}
				}

				if (is_string($algoOrCtx)) {
					return hash_final($ctx, $raw);
				} else {
					return null;
				}
			}, null, FieldOptions::class);
		}

		return (self::$hash)($object, $algoOrCtx, $raw);
	}


	/**
	 * Creates \Google\Protobuf\FieldOptions object from serialized Protocol Buffers message.
	 *
	 * @param string $input
	 * @param FieldOptions $object
	 * @param int $start
	 * @param int $end
	 *
	 * @throws \Exception
	 *
	 * @return FieldOptions
	 */
	public static function fromProtobuf($input, $object = null, &$start = 0, $end = null)
	{
		if (self::$fromProtobuf === null) {
			self::$fromProtobuf = Closure::bind(static function ($input, $object, &$start, $end) {
				if ($object === null) {
					$object = new FieldOptions();
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
							$object->ctype = Binary::decodeVarint($input, $start);
							break;
						case 2:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->packed = (bool)Binary::decodeVarint($input, $start);
							break;
						case 6:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->jstype = Binary::decodeVarint($input, $start);
							break;
						case 5:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->lazy = (bool)Binary::decodeVarint($input, $start);
							break;
						case 3:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->deprecated = (bool)Binary::decodeVarint($input, $start);
							break;
						case 10:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->weak = (bool)Binary::decodeVarint($input, $start);
							break;
						case 999:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							if (!(isset($object->uninterpretedOption) && is_array($object->uninterpretedOption))) {
								$object->uninterpretedOption = array();
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->uninterpretedOption[] = UninterpretedOptionMeta::fromProtobuf($input, null, $start, $start + $length);
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
			}, null, FieldOptions::class);
		}

		return (self::$fromProtobuf)($input, $object, $start, $end);
	}


	/**
	 * Serialized \Google\Protobuf\FieldOptions to Protocol Buffers message.
	 *
	 * @param FieldOptions $object
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return string
	 */
	public static function toProtobuf($object, $filter = null)
	{
		if (self::$toProtobuf === null) {
			self::$toProtobuf = Closure::bind(static function (FieldOptions $object, $filter) {
				$output = '';

				if (isset($object->ctype) && ($filter === null || isset($filter['ctype']))) {
					$output .= "\x08";
					$output .= Binary::encodeVarint($object->ctype);
				}

				if (isset($object->packed) && ($filter === null || isset($filter['packed']))) {
					$output .= "\x10";
					$output .= Binary::encodeVarint((int)$object->packed);
				}

				if (isset($object->jstype) && ($filter === null || isset($filter['jstype']))) {
					$output .= "\x30";
					$output .= Binary::encodeVarint($object->jstype);
				}

				if (isset($object->lazy) && ($filter === null || isset($filter['lazy']))) {
					$output .= "\x28";
					$output .= Binary::encodeVarint((int)$object->lazy);
				}

				if (isset($object->deprecated) && ($filter === null || isset($filter['deprecated']))) {
					$output .= "\x18";
					$output .= Binary::encodeVarint((int)$object->deprecated);
				}

				if (isset($object->weak) && ($filter === null || isset($filter['weak']))) {
					$output .= "\x50";
					$output .= Binary::encodeVarint((int)$object->weak);
				}

				if (isset($object->uninterpretedOption) && ($filter === null || isset($filter['uninterpretedOption']))) {
					foreach ($object->uninterpretedOption instanceof \Traversable ? $object->uninterpretedOption : (array)$object->uninterpretedOption as $k => $v) {
						$output .= "\xba\x3e";
						$buffer = UninterpretedOptionMeta::toProtobuf($v, $filter === null ? null : $filter['uninterpretedOption']);
						$output .= Binary::encodeVarint(strlen($buffer));
						$output .= $buffer;
					}
				}

				return $output;
			}, null, FieldOptions::class);
		}

		return (self::$toProtobuf)($object, $filter);
	}
}
