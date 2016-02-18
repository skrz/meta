<?php
namespace Skrz\Meta\Fields;

/**
 * Fields represent composite set of fields/properties .
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
interface FieldsInterface extends \Countable, \Iterator, \ArrayAccess
{

	/**
	 * Returns true if given field name is in set.
	 *
	 * @param string $fieldName
	 * @return bool
	 */
	public function field($fieldName);

	/**
	 * Returns nested field set for given field name, if field name is not in set, it will return empty set.
	 *
	 * @param string $fieldName
	 * @return FieldsInterface
	 */
	public function fields($fieldName);

	/**
	 * If parameter `$fieldName` is not null, it wil return {@link FieldsBuilderInterface} for given field name,
	 * if the parameters is null, it returns {@link FieldsBuilderInterface} for whole set.
	 *
	 * @param string $fieldName
	 * @return FieldsBuilderInterface
	 */
	public function builder($fieldName = null);

	/**
	 * Serialize this field set into string.
	 *
	 * @return string
	 */
	public function __toString();

}
