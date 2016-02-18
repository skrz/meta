<?php
namespace Skrz\Meta\Fixtures\Protobuf\Meta;

use Skrz\Meta\Fixtures\Protobuf\ClassWithEmbeddedMessageProperty;
use Skrz\Meta\Fixtures\Protobuf\ClassWithEmbeddedMessageProperty\Meta\EmbeddedMeta;
use Skrz\Meta\MetaInterface;
use Skrz\Meta\PHP\PhpMetaInterface;
use Skrz\Meta\Protobuf\Binary;
use Skrz\Meta\Protobuf\ProtobufException;
use Skrz\Meta\Protobuf\ProtobufMetaInterface;
use Skrz\Meta\Stack;

/**
 * Meta class for \Skrz\Meta\Fixtures\Protobuf\ClassWithEmbeddedMessageProperty
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
class ClassWithEmbeddedMessagePropertyMeta extends ClassWithEmbeddedMessageProperty implements MetaInterface, PhpMetaInterface, ProtobufMetaInterface
{
	const X_PROTOBUF_FIELD = 1;

	/** @var ClassWithEmbeddedMessagePropertyMeta */
	private static $instance;

	/**
	 * Mapping from group name to group ID for fromArray() and toArray()
	 *
	 * @var string[]
	 */
	private static $groups = array('' => 1);


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
	 * @return ClassWithEmbeddedMessagePropertyMeta
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			new self(); // self::$instance assigned in __construct
		}
		return self::$instance;
	}


	/**
	 * Creates new instance of \Skrz\Meta\Fixtures\Protobuf\ClassWithEmbeddedMessageProperty
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return ClassWithEmbeddedMessageProperty
	 */
	public static function create()
	{
		switch (func_num_args()) {
			case 0:
				return new ClassWithEmbeddedMessageProperty();
			case 1:
				return new ClassWithEmbeddedMessageProperty(func_get_arg(0));
			case 2:
				return new ClassWithEmbeddedMessageProperty(func_get_arg(0), func_get_arg(1));
			case 3:
				return new ClassWithEmbeddedMessageProperty(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			case 4:
				return new ClassWithEmbeddedMessageProperty(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
			case 5:
				return new ClassWithEmbeddedMessageProperty(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			case 6:
				return new ClassWithEmbeddedMessageProperty(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
			case 7:
				return new ClassWithEmbeddedMessageProperty(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6));
			case 8:
				return new ClassWithEmbeddedMessageProperty(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6), func_get_arg(7));
			default:
				throw new \InvalidArgumentException('More than 8 arguments supplied, please be reasonable.');
		}
	}


	/**
	 * Resets properties of \Skrz\Meta\Fixtures\Protobuf\ClassWithEmbeddedMessageProperty to default values
	 *
	 *
	 * @param ClassWithEmbeddedMessageProperty $object
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	public static function reset($object)
	{
		if (!($object instanceof ClassWithEmbeddedMessageProperty)) {
			throw new \InvalidArgumentException('You have to pass object of class Skrz\Meta\Fixtures\Protobuf\ClassWithEmbeddedMessageProperty.');
		}
		$object->x = NULL;
	}


	/**
	 * Computes hash of \Skrz\Meta\Fixtures\Protobuf\ClassWithEmbeddedMessageProperty
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

		if (isset($object->x)) {
			hash_update($ctx, 'x');
			EmbeddedMeta::hash($object->x, $ctx);
		}

		if (is_string($algoOrCtx)) {
			return hash_final($ctx, $raw);
		} else {
			return null;
		}
	}


	/**
	 * Creates \Skrz\Meta\Fixtures\Protobuf\ClassWithEmbeddedMessageProperty object from array
	 *
	 * @param array $input
	 * @param string $group
	 * @param ClassWithEmbeddedMessageProperty $object
	 *
	 * @throws \Exception
	 *
	 * @return ClassWithEmbeddedMessageProperty
	 */
	public static function fromArray($input, $group = NULL, $object = NULL)
	{
		if (!isset(self::$groups[$group])) {
			throw new \InvalidArgumentException('Group \'' . $group . '\' not supported for ' . 'Skrz\\Meta\\Fixtures\\Protobuf\\ClassWithEmbeddedMessageProperty' . '.');
		} else {
			$id = self::$groups[$group];
		}

		if ($object === null) {
			$object = new ClassWithEmbeddedMessageProperty();
		} elseif (!($object instanceof ClassWithEmbeddedMessageProperty)) {
			throw new \InvalidArgumentException('You have to pass object of class Skrz\Meta\Fixtures\Protobuf\ClassWithEmbeddedMessageProperty.');
		}

		if (($id & 1) > 0 && isset($input['x'])) {
			$object->x = EmbeddedMeta::fromArray($input['x'], $group, isset($object->x) ? $object->x : null);
		} elseif (($id & 1) > 0 && array_key_exists('x', $input) && $input['x'] === null) {
			$object->x = null;
		}

		return $object;
	}


	/**
	 * Serializes \Skrz\Meta\Fixtures\Protobuf\ClassWithEmbeddedMessageProperty to array
	 *
	 * @param ClassWithEmbeddedMessageProperty $object
	 * @param string $group
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return array
	 */
	public static function toArray($object, $group = NULL, $filter = NULL)
	{
		if ($object === null) {
			return null;
		}
		if (!isset(self::$groups[$group])) {
			throw new \InvalidArgumentException('Group \'' . $group . '\' not supported for ' . 'Skrz\\Meta\\Fixtures\\Protobuf\\ClassWithEmbeddedMessageProperty' . '.');
		} else {
			$id = self::$groups[$group];
		}

		if (!($object instanceof ClassWithEmbeddedMessageProperty)) {
			throw new \InvalidArgumentException('You have to pass object of class Skrz\Meta\Fixtures\Protobuf\ClassWithEmbeddedMessageProperty.');
		}

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
				$output['x'] = EmbeddedMeta::toArray($object->x, $group, $filter === null ? null : $filter['x']);
			}

		} catch (\Exception $e) {
			Stack::$objects->detach($object);
			throw $e;
		}

		Stack::$objects->detach($object);
		return $output;
	}


	/**
	 * Creates \Skrz\Meta\Fixtures\Protobuf\ClassWithEmbeddedMessageProperty object from object
	 *
	 * @param object $input
	 * @param string $group
	 * @param ClassWithEmbeddedMessageProperty $object
	 *
	 * @throws \Exception
	 *
	 * @return ClassWithEmbeddedMessageProperty
	 */
	public static function fromObject($input, $group = NULL, $object = NULL)
	{
		$input = (array)$input;

		if (!isset(self::$groups[$group])) {
			throw new \InvalidArgumentException('Group \'' . $group . '\' not supported for ' . 'Skrz\\Meta\\Fixtures\\Protobuf\\ClassWithEmbeddedMessageProperty' . '.');
		} else {
			$id = self::$groups[$group];
		}

		if ($object === null) {
			$object = new ClassWithEmbeddedMessageProperty();
		} elseif (!($object instanceof ClassWithEmbeddedMessageProperty)) {
			throw new \InvalidArgumentException('You have to pass object of class Skrz\Meta\Fixtures\Protobuf\ClassWithEmbeddedMessageProperty.');
		}

		if (($id & 1) > 0 && isset($input['x'])) {
			$object->x = EmbeddedMeta::fromObject($input['x'], $group, isset($object->x) ? $object->x : null);
		} elseif (($id & 1) > 0 && array_key_exists('x', $input) && $input['x'] === null) {
			$object->x = null;
		}

		return $object;
	}


	/**
	 * Serializes \Skrz\Meta\Fixtures\Protobuf\ClassWithEmbeddedMessageProperty to object
	 *
	 * @param ClassWithEmbeddedMessageProperty $object
	 * @param string $group
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return object
	 */
	public static function toObject($object, $group = NULL, $filter = NULL)
	{
		if ($object === null) {
			return null;
		}
		if (!isset(self::$groups[$group])) {
			throw new \InvalidArgumentException('Group \'' . $group . '\' not supported for ' . 'Skrz\\Meta\\Fixtures\\Protobuf\\ClassWithEmbeddedMessageProperty' . '.');
		} else {
			$id = self::$groups[$group];
		}

		if (!($object instanceof ClassWithEmbeddedMessageProperty)) {
			throw new \InvalidArgumentException('You have to pass object of class Skrz\Meta\Fixtures\Protobuf\ClassWithEmbeddedMessageProperty.');
		}

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
				$output['x'] = EmbeddedMeta::toObject($object->x, $group, $filter === null ? null : $filter['x']);
			}

		} catch (\Exception $e) {
			Stack::$objects->detach($object);
			throw $e;
		}

		Stack::$objects->detach($object);
		return (object)$output;
	}


	/**
	 * Creates \Skrz\Meta\Fixtures\Protobuf\ClassWithEmbeddedMessageProperty object from serialized Protocol Buffers message.
	 *
	 * @param string $input
	 * @param ClassWithEmbeddedMessageProperty $object
	 * @param int $start
	 * @param int $end
	 *
	 * @throws \Exception
	 *
	 * @return ClassWithEmbeddedMessageProperty
	 */
	public static function fromProtobuf($input, $object = NULL, &$start = 0, $end = NULL)
	{
		if ($object === null) {
			$object = new ClassWithEmbeddedMessageProperty();
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
					$object->x = EmbeddedMeta::fromProtobuf($input, null, $start, $start + $length);
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
	 * Serialized \Skrz\Meta\Fixtures\Protobuf\ClassWithEmbeddedMessageProperty to Protocol Buffers message.
	 *
	 * @param ClassWithEmbeddedMessageProperty $object
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return string
	 */
	public static function toProtobuf($object, $filter = NULL)
	{
		$output = '';

		if (isset($object->x) && ($filter === null || isset($filter['x']))) {
			$output .= "\x0a";
			$buffer = EmbeddedMeta::toProtobuf($object->x, $filter === null ? null : $filter['x']);
			$output .= Binary::encodeVarint(strlen($buffer));
			$output .= $buffer;
		}

		return $output;
	}

}
