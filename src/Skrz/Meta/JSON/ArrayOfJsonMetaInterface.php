<?php
namespace Skrz\Meta\JSON;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
interface ArrayOfJsonMetaInterface
{

	/**
	 * Creates object from array of JSON-serialized properties
	 *
	 * @param array $input
	 * @param string $group
	 * @param object $object
	 * @return object
	 */
	public static function fromArrayOfJson($input, $group = JsonProperty::DEFAULT_GROUP, $object = null);

	/**
	 * Transforms object into array of JSON-serialized strings
	 *
	 * @param object $object
	 * @param string $group
	 * @param int $options
	 * @return array
	 */
	public static function toArrayOfJson($object, $group = JsonProperty::DEFAULT_GROUP, $options = 0);

}
