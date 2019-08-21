<?php
namespace Skrz\Meta\Fixtures\Protobuf\Meta;

use Closure;
use Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty;
use Skrz\Meta\MetaInterface;
use Skrz\Meta\PHP\PhpMetaInterface;
use Skrz\Meta\Protobuf\Binary;
use Skrz\Meta\Protobuf\ProtobufException;
use Skrz\Meta\Protobuf\ProtobufMetaInterface;
use Skrz\Meta\Stack;

/**
 * Meta class for \Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class ClassWithVarintPropertyMeta implements MetaInterface, PhpMetaInterface, ProtobufMetaInterface
{
	const X_PROTOBUF_FIELD = 1;

	/** @var ClassWithVarintPropertyMeta */
	private static $instance;

	/** @var callable */
	private static $reset;

	/** @var callable */
	private static $hash;

	/** @var string[] */
	private static $groups = ['' => 1];

	/** @var callable */
	private static $fromArray;

	/** @var callable */
	private static $toArray;

	/** @var callable */
	private static $fromObject;

	/** @var callable */
	private static $toObject;

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
	 * @return ClassWithVarintPropertyMeta
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			new self(); // self::$instance assigned in __construct
		}
		return self::$instance;
	}


	/**
	 * Creates new instance of \Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return ClassWithVarintProperty
	 */
	public static function create()
	{
		switch (func_num_args()) {
			case 0:
				return new ClassWithVarintProperty();
			case 1:
				return new ClassWithVarintProperty(func_get_arg(0));
			case 2:
				return new ClassWithVarintProperty(func_get_arg(0), func_get_arg(1));
			case 3:
				return new ClassWithVarintProperty(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			case 4:
				return new ClassWithVarintProperty(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
			case 5:
				return new ClassWithVarintProperty(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			case 6:
				return new ClassWithVarintProperty(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
			case 7:
				return new ClassWithVarintProperty(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6));
			case 8:
				return new ClassWithVarintProperty(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6), func_get_arg(7));
			default:
				throw new \InvalidArgumentException('More than 8 arguments supplied, please be reasonable.');
		}
	}


	/**
	 * Resets properties of \Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty to default values
	 *
	 *
	 * @param ClassWithVarintProperty $object
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	public static function reset($object)
	{
		if (!($object instanceof ClassWithVarintProperty)) {
			throw new \InvalidArgumentException('You have to pass object of class Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty.');
		}

		if (self::$reset === null) {
			self::$reset = Closure::bind(static function ($object) {
				$object->x = null;
			}, null, ClassWithVarintProperty::class);
		}

		return (self::$reset)($object);
	}


	/**
	 * Computes hash of \Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty
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

				if (isset($object->x)) {
					hash_update($ctx, 'x');
					hash_update($ctx, (string)$object->x);
				}

				if (is_string($algoOrCtx)) {
					return hash_final($ctx, $raw);
				} else {
					return null;
				}
			}, null, ClassWithVarintProperty::class);
		}

		return (self::$hash)($object, $algoOrCtx, $raw);
	}


	/**
	 * Creates \Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty object from array
	 *
	 * @param array $input
	 * @param string $group
	 * @param ClassWithVarintProperty $object
	 *
	 * @throws \Exception
	 *
	 * @return ClassWithVarintProperty
	 */
	public static function fromArray($input, $group = null, $object = null)
	{
		if (!isset(self::$groups[$group])) {
			throw new \InvalidArgumentException('Group \'' . $group . '\' not supported for ' . 'Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty' . '.');
		} else {
			$id = self::$groups[$group];
		}

		if ($object === null) {
			$object = new ClassWithVarintProperty();
		} elseif (!($object instanceof ClassWithVarintProperty)) {
			throw new \InvalidArgumentException('You have to pass object of class Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty.');
		}

		if (self::$fromArray === null) {
			self::$fromArray = Closure::bind(static function ($input, $group, $object, $id) {
				if (($id & 1) > 0 && isset($input['x'])) {
					$object->x = $input['x'];
				} elseif (($id & 1) > 0 && array_key_exists('x', $input) && $input['x'] === null) {
					$object->x = null;
				}

				return $object;
			}, null, ClassWithVarintProperty::class);
		}

		return (self::$fromArray)($input, $group, $object, $id);
	}


	/**
	 * Serializes \Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty to array
	 *
	 * @param ClassWithVarintProperty $object
	 * @param string $group
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return array
	 */
	public static function toArray($object, $group = null, $filter = null)
	{
		if ($object === null) {
			return null;
		}
		if (!isset(self::$groups[$group])) {
			throw new \InvalidArgumentException('Group \'' . $group . '\' not supported for ' . 'Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty' . '.');
		} else {
			$id = self::$groups[$group];
		}

		if (!($object instanceof ClassWithVarintProperty)) {
			throw new \InvalidArgumentException('You have to pass object of class Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty.');
		}

		if (self::$toArray === null) {
			self::$toArray = Closure::bind(static function ($object, $group, $filter, $id) {
				if (Stack::$objects === null) {
					Stack::$objects = new \SplObjectStorage();
				}

				if (Stack::$objects->contains($object)) {
					return null;
				}

				Stack::$objects->attach($object);
				try {
					$output = array();

					if (($id & 1) > 0 && ($filter === null || isset($filter['x']))) {
						$output['x'] = $object->x;
					}

				} finally {
					Stack::$objects->detach($object);
				}

				return $output;
			}, null, ClassWithVarintProperty::class);
		}

		return (self::$toArray)($object, $group, $filter, $id);
	}


	/**
	 * Creates \Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty object from object
	 *
	 * @param object $input
	 * @param string $group
	 * @param ClassWithVarintProperty $object
	 *
	 * @throws \Exception
	 *
	 * @return ClassWithVarintProperty
	 */
	public static function fromObject($input, $group = null, $object = null)
	{
		$input = (array)$input;

		if (!isset(self::$groups[$group])) {
			throw new \InvalidArgumentException('Group \'' . $group . '\' not supported for ' . 'Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty' . '.');
		} else {
			$id = self::$groups[$group];
		}

		if ($object === null) {
			$object = new ClassWithVarintProperty();
		} elseif (!($object instanceof ClassWithVarintProperty)) {
			throw new \InvalidArgumentException('You have to pass object of class Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty.');
		}

		if (self::$fromObject === null) {
			self::$fromObject = Closure::bind(static function ($input, $group, $object, $id) {
				if (($id & 1) > 0 && isset($input['x'])) {
					$object->x = $input['x'];
				} elseif (($id & 1) > 0 && array_key_exists('x', $input) && $input['x'] === null) {
					$object->x = null;
				}

				return $object;
			}, null, ClassWithVarintProperty::class);
		}

		return (self::$fromObject)($input, $group, $object, $id);
	}


	/**
	 * Serializes \Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty to object
	 *
	 * @param ClassWithVarintProperty $object
	 * @param string $group
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return object
	 */
	public static function toObject($object, $group = null, $filter = null)
	{
		if ($object === null) {
			return null;
		}
		if (!isset(self::$groups[$group])) {
			throw new \InvalidArgumentException('Group \'' . $group . '\' not supported for ' . 'Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty' . '.');
		} else {
			$id = self::$groups[$group];
		}

		if (!($object instanceof ClassWithVarintProperty)) {
			throw new \InvalidArgumentException('You have to pass object of class Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty.');
		}

		if (self::$toObject === null) {
			self::$toObject = Closure::bind(static function ($object, $group, $filter, $id) {
				if (Stack::$objects === null) {
					Stack::$objects = new \SplObjectStorage();
				}

				if (Stack::$objects->contains($object)) {
					return null;
				}

				Stack::$objects->attach($object);
				try {
					$output = array();

					if (($id & 1) > 0 && ($filter === null || isset($filter['x']))) {
						$output['x'] = $object->x;
					}

				} finally {
					Stack::$objects->detach($object);
				}

				return (object)$output;
			}, null, ClassWithVarintProperty::class);
		}

		return (self::$toObject)($object, $group, $filter, $id);
	}


	/**
	 * Creates \Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty object from serialized Protocol Buffers message.
	 *
	 * @param string $input
	 * @param ClassWithVarintProperty $object
	 * @param int $start
	 * @param int $end
	 *
	 * @throws \Exception
	 *
	 * @return ClassWithVarintProperty
	 */
	public static function fromProtobuf($input, $object = null, &$start = 0, $end = null)
	{
		if (self::$fromProtobuf === null) {
			self::$fromProtobuf = Closure::bind(static function ($input, $object, &$start, $end) {
				if ($object === null) {
					$object = new ClassWithVarintProperty();
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
							$object->x = Binary::decodeVarint($input, $start);
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
			}, null, ClassWithVarintProperty::class);
		}

		return (self::$fromProtobuf)($input, $object, $start, $end);
	}


	/**
	 * Serialized \Skrz\Meta\Fixtures\Protobuf\ClassWithVarintProperty to Protocol Buffers message.
	 *
	 * @param ClassWithVarintProperty $object
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return string
	 */
	public static function toProtobuf($object, $filter = null)
	{
		if (self::$toProtobuf === null) {
			self::$toProtobuf = Closure::bind(static function (ClassWithVarintProperty $object, $filter) {
				$output = '';

				if (isset($object->x) && ($filter === null || isset($filter['x']))) {
					$output .= "\x08";
					$output .= Binary::encodeVarint($object->x);
				}

				return $output;
			}, null, ClassWithVarintProperty::class);
		}

		return (self::$toProtobuf)($object, $filter);
	}
}
