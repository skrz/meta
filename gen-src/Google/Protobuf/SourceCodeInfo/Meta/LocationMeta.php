<?php
namespace Google\Protobuf\SourceCodeInfo\Meta;

use Closure;
use Google\Protobuf\SourceCodeInfo\Location;
use Skrz\Meta\MetaInterface;
use Skrz\Meta\Protobuf\Binary;
use Skrz\Meta\Protobuf\ProtobufException;
use Skrz\Meta\Protobuf\ProtobufMetaInterface;

/**
 * Meta class for \Google\Protobuf\SourceCodeInfo\Location
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class LocationMeta implements MetaInterface, ProtobufMetaInterface
{
	const PATH_PROTOBUF_FIELD = 1;
	const SPAN_PROTOBUF_FIELD = 2;
	const LEADING_COMMENTS_PROTOBUF_FIELD = 3;
	const TRAILING_COMMENTS_PROTOBUF_FIELD = 4;
	const LEADING_DETACHED_COMMENTS_PROTOBUF_FIELD = 6;

	/** @var LocationMeta */
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
	 * @return LocationMeta
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			new self(); // self::$instance assigned in __construct
		}
		return self::$instance;
	}


	/**
	 * Creates new instance of \Google\Protobuf\SourceCodeInfo\Location
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return Location
	 */
	public static function create()
	{
		switch (func_num_args()) {
			case 0:
				return new Location();
			case 1:
				return new Location(func_get_arg(0));
			case 2:
				return new Location(func_get_arg(0), func_get_arg(1));
			case 3:
				return new Location(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			case 4:
				return new Location(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
			case 5:
				return new Location(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			case 6:
				return new Location(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
			case 7:
				return new Location(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6));
			case 8:
				return new Location(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6), func_get_arg(7));
			default:
				throw new \InvalidArgumentException('More than 8 arguments supplied, please be reasonable.');
		}
	}


	/**
	 * Resets properties of \Google\Protobuf\SourceCodeInfo\Location to default values
	 *
	 *
	 * @param Location $object
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	public static function reset($object)
	{
		if (!($object instanceof Location)) {
			throw new \InvalidArgumentException('You have to pass object of class Google\Protobuf\SourceCodeInfo\Location.');
		}

		if (self::$reset === null) {
			self::$reset = Closure::bind(static function ($object) {
				$object->path = null;
				$object->span = null;
				$object->leadingComments = null;
				$object->trailingComments = null;
				$object->leadingDetachedComments = null;
			}, null, Location::class);
		}

		return (self::$reset)($object);
	}


	/**
	 * Computes hash of \Google\Protobuf\SourceCodeInfo\Location
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

				if (isset($object->path)) {
					hash_update($ctx, 'path');
					foreach ($object->path instanceof \Traversable ? $object->path : (array)$object->path as $v0) {
						hash_update($ctx, (string)$v0);
					}
				}

				if (isset($object->span)) {
					hash_update($ctx, 'span');
					foreach ($object->span instanceof \Traversable ? $object->span : (array)$object->span as $v0) {
						hash_update($ctx, (string)$v0);
					}
				}

				if (isset($object->leadingComments)) {
					hash_update($ctx, 'leadingComments');
					hash_update($ctx, (string)$object->leadingComments);
				}

				if (isset($object->trailingComments)) {
					hash_update($ctx, 'trailingComments');
					hash_update($ctx, (string)$object->trailingComments);
				}

				if (isset($object->leadingDetachedComments)) {
					hash_update($ctx, 'leadingDetachedComments');
					foreach ($object->leadingDetachedComments instanceof \Traversable ? $object->leadingDetachedComments : (array)$object->leadingDetachedComments as $v0) {
						hash_update($ctx, (string)$v0);
					}
				}

				if (is_string($algoOrCtx)) {
					return hash_final($ctx, $raw);
				} else {
					return null;
				}
			}, null, Location::class);
		}

		return (self::$hash)($object, $algoOrCtx, $raw);
	}


	/**
	 * Creates \Google\Protobuf\SourceCodeInfo\Location object from serialized Protocol Buffers message.
	 *
	 * @param string $input
	 * @param Location $object
	 * @param int $start
	 * @param int $end
	 *
	 * @throws \Exception
	 *
	 * @return Location
	 */
	public static function fromProtobuf($input, $object = null, &$start = 0, $end = null)
	{
		if (self::$fromProtobuf === null) {
			self::$fromProtobuf = Closure::bind(static function ($input, $object, &$start, $end) {
				if ($object === null) {
					$object = new Location();
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
							if (!(isset($object->path) && is_array($object->path))) {
								$object->path = array();
							}
							$packedLength = Binary::decodeVarint($input, $start);
							$expectedPacked = $start + $packedLength;
							if ($expectedPacked > $end) {
								throw new ProtobufException('Not enough data.');
							}
							while ($start < $expectedPacked) {
								$object->path[] = Binary::decodeVarint($input, $start);
							}
							if ($start !== $expectedPacked) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedPacked . ', got ' . $start . '.', $number);
							}
							break;
						case 2:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							if (!(isset($object->span) && is_array($object->span))) {
								$object->span = array();
							}
							$packedLength = Binary::decodeVarint($input, $start);
							$expectedPacked = $start + $packedLength;
							if ($expectedPacked > $end) {
								throw new ProtobufException('Not enough data.');
							}
							while ($start < $expectedPacked) {
								$object->span[] = Binary::decodeVarint($input, $start);
							}
							if ($start !== $expectedPacked) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedPacked . ', got ' . $start . '.', $number);
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
							$object->leadingComments = substr($input, $start, $length);
							$start += $length;
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 4:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->trailingComments = substr($input, $start, $length);
							$start += $length;
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 6:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							if (!(isset($object->leadingDetachedComments) && is_array($object->leadingDetachedComments))) {
								$object->leadingDetachedComments = array();
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->leadingDetachedComments[] = substr($input, $start, $length);
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
			}, null, Location::class);
		}

		return (self::$fromProtobuf)($input, $object, $start, $end);
	}


	/**
	 * Serialized \Google\Protobuf\SourceCodeInfo\Location to Protocol Buffers message.
	 *
	 * @param Location $object
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return string
	 */
	public static function toProtobuf($object, $filter = null)
	{
		if (self::$toProtobuf === null) {
			self::$toProtobuf = Closure::bind(static function (Location $object, $filter) {
				$output = '';

				if (isset($object->path) && ($filter === null || isset($filter['path']))) {
					$packedBuffer = '';
					$output .= "\x0a";
					foreach ($object->path instanceof \Traversable ? $object->path : (array)$object->path as $k => $v) {
						$packedBuffer .= Binary::encodeVarint($v);
					}
					$output .= Binary::encodeVarint(strlen($packedBuffer));
					$output .= $packedBuffer;
				}

				if (isset($object->span) && ($filter === null || isset($filter['span']))) {
					$packedBuffer = '';
					$output .= "\x12";
					foreach ($object->span instanceof \Traversable ? $object->span : (array)$object->span as $k => $v) {
						$packedBuffer .= Binary::encodeVarint($v);
					}
					$output .= Binary::encodeVarint(strlen($packedBuffer));
					$output .= $packedBuffer;
				}

				if (isset($object->leadingComments) && ($filter === null || isset($filter['leadingComments']))) {
					$output .= "\x1a";
					$output .= Binary::encodeVarint(strlen($object->leadingComments));
					$output .= $object->leadingComments;
				}

				if (isset($object->trailingComments) && ($filter === null || isset($filter['trailingComments']))) {
					$output .= "\x22";
					$output .= Binary::encodeVarint(strlen($object->trailingComments));
					$output .= $object->trailingComments;
				}

				if (isset($object->leadingDetachedComments) && ($filter === null || isset($filter['leadingDetachedComments']))) {
					foreach ($object->leadingDetachedComments instanceof \Traversable ? $object->leadingDetachedComments : (array)$object->leadingDetachedComments as $k => $v) {
						$output .= "\x32";
						$output .= Binary::encodeVarint(strlen($v));
						$output .= $v;
					}
				}

				return $output;
			}, null, Location::class);
		}

		return (self::$toProtobuf)($object, $filter);
	}
}
