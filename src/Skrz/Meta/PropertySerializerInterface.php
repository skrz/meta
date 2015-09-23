<?php
namespace Skrz\Meta;

use Skrz\Meta\PHP\StatementAndExpressionVO;
use Skrz\Meta\Reflection\Property;

interface PropertySerializerInterface
{

	/**
	 * @param Property $property
	 * @param string $group
	 * @return boolean
	 */
	public function matchesSerialize(Property $property, $group);

	/**
	 * @param Property $property
	 * @param string $group
	 * @return boolean
	 */
	public function matchesDeserialize(Property $property, $group);

	/**
	 * @param Property $property
	 * @param string $group
	 * @param string $inputExpression
	 * @return StatementAndExpressionVO
	 */
	public function serialize(Property $property, $group, $inputExpression);

	/**
	 * @param Property $property
	 * @param string $group
	 * @param string $inputExpression
	 * @return StatementAndExpressionVO
	 */
	public function deserialize(Property $property, $group, $inputExpression);

}
