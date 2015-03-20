<?php
namespace Skrz\Meta\JSON;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
interface JsonMetaInterface
{

	/**
	 * Creates object from JSON array of arrays / serialized string
	 *
	 * @param string|array $json
	 * @param string $group
	 * @param object $object
	 * @return object
	 */
	public static function fromJson($json, $group = null, $object = null);

	/**
	 * Serializes object into JSON array of arrays
	 *
	 * @param object $object
	 * @param string $group
	 * @param int $options
	 * @return array
	 */
	public static function toJson($object, $group = null, $options = 0);

}
