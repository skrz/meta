<?php
namespace Skrz\Meta\PHP;

use Skrz\Meta\Reflection\ArrayType;
use Skrz\Meta\Reflection\Property;
use Skrz\Meta\Reflection\Type;

class DateTimeFormattingSerializer implements PropertySerializerInterface
{

	/** @var string */
	private $format;

	/** @var string[] */
	private $groups = null;

	public function __construct($format)
	{
		$this->format = $format;
	}

	public function addGroup($group)
	{
		$this->groups = (array)$this->groups;
		$this->groups[] = $group;
		return $this;
	}

	public function matches(Property $property, $group)
	{
		$baseType = $property->getType();
		while ($baseType instanceof ArrayType) {
			$baseType = $baseType->getBaseType();
		}

		return
			($this->groups === null || in_array($group, $this->groups)) &&
			$baseType instanceof Type &&
			strtolower($baseType->getName()) === "datetime";
	}

	public function matchesSerialize(Property $property, $group)
	{
		return $this->matches($property, $group);
	}

	public function matchesDeserialize(Property $property, $group)
	{
		return $this->matches($property, $group);
	}

	public function serialize(Property $property, $group, $inputExpression)
	{
		return StatementAndExpressionVO::withStatementAndExpression(
			"if ({$inputExpression} === null) {\n" .
			"\t\$datetimeStringReturn = null;\n" .
			"} elseif ({$inputExpression} instanceof \\DateTime) {\n" .
			"\t\$datetimeStringReturn = {$inputExpression}->format(" . var_export($this->format, true) . ");\n" .
			"} elseif (is_numeric({$inputExpression})) {\n" .
			"\t\$datetimeStringReturn = (new \\DateTime('@' . intval({$inputExpression})))->format(" . var_export($this->format, true) . ");\n" .
			"} elseif (is_string({$inputExpression})) {\n" .
			"\t\$datetimeStringReturn = (new \\DateTime({$inputExpression}))->format(" . var_export($this->format, true) . ");\n" .
			"} elseif (is_array({$inputExpression}) && isset({$inputExpression}['date'])) {\n" .
			"\t\$datetimeStringReturn = (new \\DateTime({$inputExpression}['date']))->format(" . var_export($this->format, true) . ");\n" .
			"} else {\n" .
			"\tthrow new \\InvalidArgumentException('Could not serialize date of format ' . " . var_export($this->format, true) . " . '.');\n" .
			"}",
			"\$datetimeStringReturn"
		);
	}

	public function deserialize(Property $property, $group, $inputExpression)
	{
		return StatementAndExpressionVO::withStatementAndExpression(
			"if ({$inputExpression} instanceof \\DateTime) {\n" .
			"\t\$datetimeInstanceReturn = {$inputExpression};\n" .
			"} elseif (is_numeric({$inputExpression})) {\n" .
			"\t\$datetimeInstanceReturn = new \\DateTime('@' . intval({$inputExpression}));\n" .
			"} elseif (is_string({$inputExpression})) {\n" .
			"\tif ({$inputExpression} === '0000-00-00 00:00:00') {\n" .
			"\t\t\$datetimeInstanceReturn = null;\n" .
			"\t} else {\n" .
			"\t\t\$datetimeInstanceReturn = \\DateTime::createFromFormat(" . var_export($this->format, true) . ", {$inputExpression});\n" .
			"\t}\n" .
			"} elseif (is_array({$inputExpression}) && isset({$inputExpression}['date'])) {\n" .
			"\t\$datetimeInstanceReturn = new \\DateTime({$inputExpression}['date']);\n" .
			"} elseif ({$inputExpression} === null) {\n" .
			"\t\$datetimeInstanceReturn = null;\n" .
			"} else {\n" .
			"\tthrow new \\InvalidArgumentException('Could not deserialize date of format ' . " . var_export($this->format, true) . " . '.');\n" .
			"}",
			"\$datetimeInstanceReturn"
		);
	}

}
