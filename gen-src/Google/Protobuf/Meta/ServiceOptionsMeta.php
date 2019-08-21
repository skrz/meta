<?php
namespace Google\Protobuf\Meta;

use Closure;
use Google\Protobuf\ServiceOptions;
use Skrz\Meta\MetaInterface;
use Skrz\Meta\Protobuf\Binary;
use Skrz\Meta\Protobuf\ProtobufException;
use Skrz\Meta\Protobuf\ProtobufMetaInterface;

/**
 * Meta class for \Google\Protobuf\ServiceOptions
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class ServiceOptionsMeta implements MetaInterface, ProtobufMetaInterface
{
	const DEPRECATED_PROTOBUF_FIELD = 33;
	const UNINTERPRETED_OPTION_PROTOBUF_FIELD = 999;

	/** @var ServiceOptionsMeta */
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
	 * @return ServiceOptionsMeta
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			new self(); // self::$instance assigned in __construct
		}
		return self::$instance;
	}


	/**
	 * Creates new instance of \Google\Protobuf\ServiceOptions
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return ServiceOptions
	 */
	public static function create()
	{
		switch (func_num_args()) {
			case 0:
				return new ServiceOptions();
			case 1:
				return new ServiceOptions(func_get_arg(0));
			case 2:
				return new ServiceOptions(func_get_arg(0), func_get_arg(1));
			case 3:
				return new ServiceOptions(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			case 4:
				return new ServiceOptions(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
			case 5:
				return new ServiceOptions(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			case 6:
				return new ServiceOptions(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
			case 7:
				return new ServiceOptions(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6));
			case 8:
				return new ServiceOptions(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6), func_get_arg(7));
			default:
				throw new \InvalidArgumentException('More than 8 arguments supplied, please be reasonable.');
		}
	}


	/**
	 * Resets properties of \Google\Protobuf\ServiceOptions to default values
	 *
	 *
	 * @param ServiceOptions $object
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	public static function reset($object)
	{
		if (!($object instanceof ServiceOptions)) {
			throw new \InvalidArgumentException('You have to pass object of class Google\Protobuf\ServiceOptions.');
		}

		if (self::$reset === null) {
			self::$reset = Closure::bind(static function ($object) {
				$object->deprecated = null;
				$object->uninterpretedOption = null;
			}, null, ServiceOptions::class);
		}

		return (self::$reset)($object);
	}


	/**
	 * Computes hash of \Google\Protobuf\ServiceOptions
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

				if (isset($object->deprecated)) {
					hash_update($ctx, 'deprecated');
					hash_update($ctx, (string)$object->deprecated);
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
			}, null, ServiceOptions::class);
		}

		return (self::$hash)($object, $algoOrCtx, $raw);
	}


	/**
	 * Creates \Google\Protobuf\ServiceOptions object from serialized Protocol Buffers message.
	 *
	 * @param string $input
	 * @param ServiceOptions $object
	 * @param int $start
	 * @param int $end
	 *
	 * @throws \Exception
	 *
	 * @return ServiceOptions
	 */
	public static function fromProtobuf($input, $object = null, &$start = 0, $end = null)
	{
		if (self::$fromProtobuf === null) {
			self::$fromProtobuf = Closure::bind(static function ($input, $object, &$start, $end) {
				if ($object === null) {
					$object = new ServiceOptions();
				}

				if ($end === null) {
					$end = strlen($input);
				}

				while ($start < $end) {
					$tag = Binary::decodeVarint($input, $start);
					$wireType = $tag & 0x7;
					$number = $tag >> 3;
					switch ($number) {
						case 33:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->deprecated = (bool)Binary::decodeVarint($input, $start);
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
			}, null, ServiceOptions::class);
		}

		return (self::$fromProtobuf)($input, $object, $start, $end);
	}


	/**
	 * Serialized \Google\Protobuf\ServiceOptions to Protocol Buffers message.
	 *
	 * @param ServiceOptions $object
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return string
	 */
	public static function toProtobuf($object, $filter = null)
	{
		if (self::$toProtobuf === null) {
			self::$toProtobuf = Closure::bind(static function (ServiceOptions $object, $filter) {
				$output = '';

				if (isset($object->deprecated) && ($filter === null || isset($filter['deprecated']))) {
					$output .= "\x88\x02";
					$output .= Binary::encodeVarint((int)$object->deprecated);
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
			}, null, ServiceOptions::class);
		}

		return (self::$toProtobuf)($object, $filter);
	}
}
