<?php
namespace Skrz\Meta\Protobuf;

use Skrz\Meta\Fields\FieldsInterface;
use Skrz\Meta\MetaInterface;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
interface ProtobufMetaInterface extends MetaInterface
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
	 * @param array|FieldsInterface $filter
	 *
	 * @return array
	 */
	public static function toProtobuf($object, $filter = null);

}
