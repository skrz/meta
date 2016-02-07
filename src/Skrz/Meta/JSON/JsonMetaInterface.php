<?php
namespace Skrz\Meta\JSON;

use Skrz\Meta\Fields\FieldsInterface;
use Skrz\Meta\MetaInterface;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
interface JsonMetaInterface extends MetaInterface
{

	/**
	 * Creates object from JSON array of arrays / serialized string
	 *
	 * @param string|array $json
	 * @param string $group
	 * @param object $object
	 *
	 * @return object
	 */
	public static function fromJson($json, $group = JsonProperty::DEFAULT_GROUP, $object = null);

	/**
	 * Serializes object into JSON string
	 *
	 * @param object $object
	 * @param string $group
	 * @param array|FieldsInterface|int $filterOrOptions
	 * @param int $options
	 *
	 * @return array
	 */
	public static function toJson($object, $group = JsonProperty::DEFAULT_GROUP, $filterOrOptions = null, $options = 0);

	/**
	 * Creates object from array of JSON-serialized properties
	 *
	 * @param array $input
	 * @param string $group
	 * @param object $object
	 *
	 * @return object
	 */
	public static function fromArrayOfJson($input, $group = JsonProperty::DEFAULT_GROUP, $object = null);

	/**
	 * Transforms object into array of JSON-serialized strings
	 *
	 * @param object $object
	 * @param string $group
	 * @param array|FieldsInterface|int $filterOrOptions
	 * @param int $options
	 *
	 * @return array
	 */
	public static function toArrayOfJson($object, $group = JsonProperty::DEFAULT_GROUP, $filterOrOptions = null, $options = 0);

}
