<?php
namespace Skrz\Meta\Protobuf;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
interface ProtobufMetaInterface
{

	/**
	 * Creates object from serialized Protocol Buffers message.
	 *
	 * @param string $input
	 * @param object $object
	 * @param int $start inclusive
	 * @param int $end exclusive
	 *
	 * @return object
	 */
	public static function fromProtobuf($input, $object = null, &$start = 0, $end = null);

	/**
	 * Serializes object state to Protocol Buffers message.
	 *
	 * @param object $object
	 * @param array $filter
	 *
	 * @return array
	 */
	public static function toProtobuf($object, array $filter = null);

}
