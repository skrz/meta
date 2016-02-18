<?php
namespace Skrz\Meta\PHP;

use Skrz\Meta\Fields\FieldsInterface;
use Skrz\Meta\MetaInterface;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
interface PhpMetaInterface extends MetaInterface
{

	/**
	 * Creates object from array
	 *
	 * @param array $input
	 * @param string $group
	 * @param object $object
	 *
	 * @return object
	 */
	public static function fromArray($input, $group = PhpArrayOffset::DEFAULT_GROUP, $object = null);

	/**
	 * Serializes object state to array
	 *
	 * @param object $object
	 * @param string $group
	 * @param array|FieldsInterface $filter
	 *
	 * @return array
	 */
	public static function toArray($object, $group = PhpArrayOffset::DEFAULT_GROUP, $filter = null);

	/**
	 * Creates object from any object with public properties (mostly \stdClass)
	 *
	 * @param object $input
	 * @param string $group
	 * @param object $object
	 *
	 * @return object
	 */
	public static function fromObject($input, $group = PhpArrayOffset::DEFAULT_GROUP, $object = null);

	/**
	 * Serializes object state to \stdClass
	 *
	 * @param object $object
	 * @param string $group
	 * @param array|FieldsInterface $filter
	 *
	 * @return \stdClass
	 */
	public static function toObject($object, $group = PhpArrayOffset::DEFAULT_GROUP, $filter = null);

}
