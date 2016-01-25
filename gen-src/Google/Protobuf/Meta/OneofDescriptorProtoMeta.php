<?php
namespace Google\Protobuf\Meta;

use Google\Protobuf\OneofDescriptorProto;
use Skrz\Meta\MetaInterface;
use Skrz\Meta\Protobuf\Binary;
use Skrz\Meta\Protobuf\ProtobufException;
use Skrz\Meta\Protobuf\ProtobufMetaInterface;

/**
 * Meta class for \Google\Protobuf\OneofDescriptorProto
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
class OneofDescriptorProtoMeta extends OneofDescriptorProto implements MetaInterface, ProtobufMetaInterface
{
	const NAME_PROTOBUF_FIELD = 1;

	/** @var OneofDescriptorProtoMeta */
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
	 * @return OneofDescriptorProtoMeta
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			new self(); // self::$instance assigned in __construct
		}
		return self::$instance;
	}


	/**
	 * Creates new instance of \Google\Protobuf\OneofDescriptorProto
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return OneofDescriptorProto
	 */
	public static function create()
	{
		switch (func_num_args()) {
			case 0:
				return new OneofDescriptorProto();
			case 1:
				return new OneofDescriptorProto(func_get_arg(0));
			case 2:
				return new OneofDescriptorProto(func_get_arg(0), func_get_arg(1));
			case 3:
				return new OneofDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			case 4:
				return new OneofDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
			case 5:
				return new OneofDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			case 6:
				return new OneofDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
			case 7:
				return new OneofDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6));
			case 8:
				return new OneofDescriptorProto(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6), func_get_arg(7));
			default:
				throw new \InvalidArgumentException('More than 8 arguments supplied, please be reasonable.');
		}
	}


	/**
	 * Resets properties of \Google\Protobuf\OneofDescriptorProto to default values
	 *
	 *
	 * @param OneofDescriptorProto $object
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	public static function reset($object)
	{
		if (!($object instanceof OneofDescriptorProto)) {
			throw new \InvalidArgumentException('You have to pass object of class Google\Protobuf\OneofDescriptorProto.');
		}
		$object->name = NULL;
	}


	/**
	 * Computes hash of \Google\Protobuf\OneofDescriptorProto
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

		if (isset($object->name)) {
			hash_update($ctx, 'name');
			hash_update($ctx, (string)$object->name);
		}

		if (is_string($algoOrCtx)) {
			return hash_final($ctx, $raw);
		} else {
			return null;
		}
	}


	/**
	 * Creates \Google\Protobuf\OneofDescriptorProto object from serialized Protocol Buffers message.
	 *
	 * @param string $input
	 * @param OneofDescriptorProto $object
	 * @param int $start
	 * @param int $end
	 *
	 * @throws \Exception
	 *
	 * @return OneofDescriptorProto
	 */
	public static function fromProtobuf($input, $object = NULL, &$start = 0, $end = NULL)
	{
		if ($object === null) {
			$object = new OneofDescriptorProto();
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
	 * Serialized \Google\Protobuf\OneofDescriptorProto to Protocol Buffers message.
	 *
	 * @param OneofDescriptorProto $object
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return string
	 */
	public static function toProtobuf($object, array $filter = NULL)
	{
		$output = '';

		if (isset($object->name) && ($filter === null || isset($filter['name']))) {
			$output .= "\x0a";
			$output .= Binary::encodeVarint(strlen($object->name));
			$output .= $object->name;
		}

		return $output;
	}

}
