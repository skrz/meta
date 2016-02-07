<?php
namespace Skrz\Meta\Fields;

/**
 * Builder allows to update fields in field set.
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
interface FieldsBuilderInterface extends FieldsInterface
{

	/**
	 * Set field to be 'scalar' - ie. it is not composite, has no child fields.
	 *
	 * @param string $fieldName
	 * @return FieldsBuilderInterface
	 */
	public function appendField($fieldName);

	/**
	 * Update field to given `$fields` instance.
	 *
	 * @param string $fieldName
	 * @param FieldsBuilderInterface $fields
	 * @return FieldsBuilderInterface
	 */
	public function appendFields($fieldName, FieldsBuilderInterface $fields);

	/**
	 * Return {@link FieldsInterface} from this builder - can optimize inner structure for further faster access.
	 *
	 * @return FieldsInterface
	 */
	public function build();

}
