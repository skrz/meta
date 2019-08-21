<?php
namespace Google\Protobuf\Meta;

use Closure;
use Google\Protobuf\UninterpretedOption;
use Google\Protobuf\UninterpretedOption\Meta\NamePartMeta;
use Skrz\Meta\MetaInterface;
use Skrz\Meta\Protobuf\Binary;
use Skrz\Meta\Protobuf\ProtobufException;
use Skrz\Meta\Protobuf\ProtobufMetaInterface;

/**
 * Meta class for \Google\Protobuf\UninterpretedOption
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class UninterpretedOptionMeta implements MetaInterface, ProtobufMetaInterface
{
	const NAME_PROTOBUF_FIELD = 2;
	const IDENTIFIER_VALUE_PROTOBUF_FIELD = 3;
	const POSITIVE_INT_VALUE_PROTOBUF_FIELD = 4;
	const NEGATIVE_INT_VALUE_PROTOBUF_FIELD = 5;
	const DOUBLE_VALUE_PROTOBUF_FIELD = 6;
	const STRING_VALUE_PROTOBUF_FIELD = 7;
	const AGGREGATE_VALUE_PROTOBUF_FIELD = 8;

	/** @var UninterpretedOptionMeta */
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
	 * @return UninterpretedOptionMeta
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			new self(); // self::$instance assigned in __construct
		}
		return self::$instance;
	}


	/**
	 * Creates new instance of \Google\Protobuf\UninterpretedOption
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return UninterpretedOption
	 */
	public static function create()
	{
		switch (func_num_args()) {
			case 0:
				return new UninterpretedOption();
			case 1:
				return new UninterpretedOption(func_get_arg(0));
			case 2:
				return new UninterpretedOption(func_get_arg(0), func_get_arg(1));
			case 3:
				return new UninterpretedOption(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			case 4:
				return new UninterpretedOption(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
			case 5:
				return new UninterpretedOption(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			case 6:
				return new UninterpretedOption(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
			case 7:
				return new UninterpretedOption(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6));
			case 8:
				return new UninterpretedOption(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6), func_get_arg(7));
			default:
				throw new \InvalidArgumentException('More than 8 arguments supplied, please be reasonable.');
		}
	}


	/**
	 * Resets properties of \Google\Protobuf\UninterpretedOption to default values
	 *
	 *
	 * @param UninterpretedOption $object
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	public static function reset($object)
	{
		if (!($object instanceof UninterpretedOption)) {
			throw new \InvalidArgumentException('You have to pass object of class Google\Protobuf\UninterpretedOption.');
		}

		if (self::$reset === null) {
			self::$reset = Closure::bind(static function ($object) {
				$object->name = null;
				$object->identifierValue = null;
				$object->positiveIntValue = null;
				$object->negativeIntValue = null;
				$object->doubleValue = null;
				$object->stringValue = null;
				$object->aggregateValue = null;
			}, null, UninterpretedOption::class);
		}

		return (self::$reset)($object);
	}


	/**
	 * Computes hash of \Google\Protobuf\UninterpretedOption
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
					foreach ($object->name instanceof \Traversable ? $object->name : (array)$object->name as $v0) {
						NamePartMeta::hash($v0, $ctx);
					}
				}

				if (isset($object->identifierValue)) {
					hash_update($ctx, 'identifierValue');
					hash_update($ctx, (string)$object->identifierValue);
				}

				if (isset($object->positiveIntValue)) {
					hash_update($ctx, 'positiveIntValue');
					hash_update($ctx, (string)$object->positiveIntValue);
				}

				if (isset($object->negativeIntValue)) {
					hash_update($ctx, 'negativeIntValue');
					hash_update($ctx, (string)$object->negativeIntValue);
				}

				if (isset($object->doubleValue)) {
					hash_update($ctx, 'doubleValue');
					hash_update($ctx, (string)$object->doubleValue);
				}

				if (isset($object->stringValue)) {
					hash_update($ctx, 'stringValue');
					hash_update($ctx, (string)$object->stringValue);
				}

				if (isset($object->aggregateValue)) {
					hash_update($ctx, 'aggregateValue');
					hash_update($ctx, (string)$object->aggregateValue);
				}

				if (is_string($algoOrCtx)) {
					return hash_final($ctx, $raw);
				} else {
					return null;
				}
			}, null, UninterpretedOption::class);
		}

		return (self::$hash)($object, $algoOrCtx, $raw);
	}


	/**
	 * Creates \Google\Protobuf\UninterpretedOption object from serialized Protocol Buffers message.
	 *
	 * @param string $input
	 * @param UninterpretedOption $object
	 * @param int $start
	 * @param int $end
	 *
	 * @throws \Exception
	 *
	 * @return UninterpretedOption
	 */
	public static function fromProtobuf($input, $object = null, &$start = 0, $end = null)
	{
		if (self::$fromProtobuf === null) {
			self::$fromProtobuf = Closure::bind(static function ($input, $object, &$start, $end) {
				if ($object === null) {
					$object = new UninterpretedOption();
				}

				if ($end === null) {
					$end = strlen($input);
				}

				while ($start < $end) {
					$tag = Binary::decodeVarint($input, $start);
					$wireType = $tag & 0x7;
					$number = $tag >> 3;
					switch ($number) {
						case 2:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							if (!(isset($object->name) && is_array($object->name))) {
								$object->name = array();
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->name[] = NamePartMeta::fromProtobuf($input, null, $start, $start + $length);
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
							$object->identifierValue = substr($input, $start, $length);
							$start += $length;
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 4:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->positiveIntValue = Binary::decodeVarint($input, $start);
							break;
						case 5:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->negativeIntValue = Binary::decodeVarint($input, $start);
							break;
						case 6:
							if ($wireType !== 1) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 1.', $number);
							}
							$expectedStart = $start + 8;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->doubleValue = Binary::decodeDouble($input, $start);
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
							$object->stringValue = substr($input, $start, $length);
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
							$object->aggregateValue = substr($input, $start, $length);
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
			}, null, UninterpretedOption::class);
		}

		return (self::$fromProtobuf)($input, $object, $start, $end);
	}


	/**
	 * Serialized \Google\Protobuf\UninterpretedOption to Protocol Buffers message.
	 *
	 * @param UninterpretedOption $object
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return string
	 */
	public static function toProtobuf($object, $filter = null)
	{
		if (self::$toProtobuf === null) {
			self::$toProtobuf = Closure::bind(static function (UninterpretedOption $object, $filter) {
				$output = '';

				if (isset($object->name) && ($filter === null || isset($filter['name']))) {
					foreach ($object->name instanceof \Traversable ? $object->name : (array)$object->name as $k => $v) {
						$output .= "\x12";
						$buffer = NamePartMeta::toProtobuf($v, $filter === null ? null : $filter['name']);
						$output .= Binary::encodeVarint(strlen($buffer));
						$output .= $buffer;
					}
				}

				if (isset($object->identifierValue) && ($filter === null || isset($filter['identifierValue']))) {
					$output .= "\x1a";
					$output .= Binary::encodeVarint(strlen($object->identifierValue));
					$output .= $object->identifierValue;
				}

				if (isset($object->positiveIntValue) && ($filter === null || isset($filter['positiveIntValue']))) {
					$output .= "\x20";
					$output .= Binary::encodeVarint($object->positiveIntValue);
				}

				if (isset($object->negativeIntValue) && ($filter === null || isset($filter['negativeIntValue']))) {
					$output .= "\x28";
					$output .= Binary::encodeVarint($object->negativeIntValue);
				}

				if (isset($object->doubleValue) && ($filter === null || isset($filter['doubleValue']))) {
					$output .= "\x31";
					$output .= Binary::encodeDouble($object->doubleValue);
				}

				if (isset($object->stringValue) && ($filter === null || isset($filter['stringValue']))) {
					$output .= "\x3a";
					$output .= Binary::encodeVarint(strlen($object->stringValue));
					$output .= $object->stringValue;
				}

				if (isset($object->aggregateValue) && ($filter === null || isset($filter['aggregateValue']))) {
					$output .= "\x42";
					$output .= Binary::encodeVarint(strlen($object->aggregateValue));
					$output .= $object->aggregateValue;
				}

				return $output;
			}, null, UninterpretedOption::class);
		}

		return (self::$toProtobuf)($object, $filter);
	}
}
