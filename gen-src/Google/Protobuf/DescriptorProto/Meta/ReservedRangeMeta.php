<?php
namespace Google\Protobuf\DescriptorProto\Meta;

use Google\Protobuf\DescriptorProto\ReservedRange;
use Skrz\Meta\MetaInterface;
use Skrz\Meta\Protobuf\Binary;
use Skrz\Meta\Protobuf\ProtobufException;
use Skrz\Meta\Protobuf\ProtobufMetaInterface;

/**
 * Meta class for \Google\Protobuf\DescriptorProto\ReservedRange
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
class ReservedRangeMeta extends ReservedRange implements MetaInterface, ProtobufMetaInterface
{
	const START_PROTOBUF_FIELD = 1;
	const END_PROTOBUF_FIELD = 2;

	/** @var ReservedRangeMeta */
	private static $instance;


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
	 * @return ReservedRangeMeta
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			new self(); // self::$instance assigned in __construct
		}
		return self::$instance;
	}


	/**
	 * Creates new instance of \Google\Protobuf\DescriptorProto\ReservedRange
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return ReservedRange
	 */
	public static function create()
	{
		switch (func_num_args()) {
			case 0:
				return new ReservedRange();
			case 1:
				return new ReservedRange(func_get_arg(0));
			case 2:
				return new ReservedRange(func_get_arg(0), func_get_arg(1));
			case 3:
				return new ReservedRange(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			case 4:
				return new ReservedRange(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
			case 5:
				return new ReservedRange(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			case 6:
				return new ReservedRange(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
			case 7:
				return new ReservedRange(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6));
			case 8:
				return new ReservedRange(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6), func_get_arg(7));
			default:
				throw new \InvalidArgumentException('More than 8 arguments supplied, please be reasonable.');
		}
	}


	/**
	 * Resets properties of \Google\Protobuf\DescriptorProto\ReservedRange to default values
	 *
	 *
	 * @param ReservedRange $object
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	public static function reset($object)
	{
		if (!($object instanceof ReservedRange)) {
			throw new \InvalidArgumentException('You have to pass object of class Google\Protobuf\DescriptorProto\ReservedRange.');
		}
		$object->start = NULL;
		$object->end = NULL;
	}


	/**
	 * Computes hash of \Google\Protobuf\DescriptorProto\ReservedRange
	 *
	 * @param object $object
	 * @param string|resource $algoOrCtx
	 * @param bool $raw
	 *
	 * @return string|void
	 */
	public static function hash($object, $algoOrCtx = 'md5', $raw = FALSE)
	{
		if (is_string($algoOrCtx)) {
			$ctx = hash_init($algoOrCtx);
		} else {
			$ctx = $algoOrCtx;
		}

		if (isset($object->start)) {
			hash_update($ctx, 'start');
			hash_update($ctx, (string)$object->start);
		}

		if (isset($object->end)) {
			hash_update($ctx, 'end');
			hash_update($ctx, (string)$object->end);
		}

		if (is_string($algoOrCtx)) {
			return hash_final($ctx, $raw);
		} else {
			return null;
		}
	}


	/**
	 * Creates \Google\Protobuf\DescriptorProto\ReservedRange object from serialized Protocol Buffers message.
	 *
	 * @param string $input
	 * @param ReservedRange $object
	 * @param int $start
	 * @param int $end
	 *
	 * @throws \Exception
	 *
	 * @return ReservedRange
	 */
	public static function fromProtobuf($input, $object = NULL, &$start = 0, $end = NULL)
	{
		if ($object === null) {
			$object = new ReservedRange();
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
					$object->start = Binary::decodeVarint($input, $start);
					break;
				case 2:
					if ($wireType !== 0) {
						throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
					}
					$object->end = Binary::decodeVarint($input, $start);
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
	}


	/**
	 * Serialized \Google\Protobuf\DescriptorProto\ReservedRange to Protocol Buffers message.
	 *
	 * @param ReservedRange $object
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return string
	 */
	public static function toProtobuf($object, $filter = NULL)
	{
		$output = '';

		if (isset($object->start) && ($filter === null || isset($filter['start']))) {
			$output .= "\x08";
			$output .= Binary::encodeVarint($object->start);
		}

		if (isset($object->end) && ($filter === null || isset($filter['end']))) {
			$output .= "\x10";
			$output .= Binary::encodeVarint($object->end);
		}

		return $output;
	}

}
