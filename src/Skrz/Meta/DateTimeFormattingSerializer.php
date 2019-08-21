<?php
namespace Skrz\Meta;

use Nette\PhpGenerator\Helpers;
use Skrz\Meta\PHP\StatementAndExpressionVO;
use Skrz\Meta\Reflection\ArrayType;
use Skrz\Meta\Reflection\Property;
use Skrz\Meta\Reflection\Type;

class DateTimeFormattingSerializer implements PropertySerializerInterface
{

	/** @var string */
	private $format;

	/** @var string */
	private $dateTimeClass;

	/** @var string[] */
	private $groups = null;

	private $emptyValue;

	public function __construct($format, $dateTimeClass = \DateTime::class, $emptyValue = '0000-00-00 00:00:00')
	{
		$this->format = $format;
		$this->dateTimeClass = $dateTimeClass;
		if (!substr($dateTimeClass, 0, 1) != "\\") {
			$this->dateTimeClass = "\\" . $this->dateTimeClass;
		}
		$this->emptyValue = $emptyValue;
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
			$baseType instanceof Type && $baseType->isDateTime();

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
			"} elseif ({$inputExpression} instanceof \\DateTimeInterface) {\n" .
			"\t\$datetimeStringReturn = {$inputExpression}->format(" . Helpers::dump($this->format) . ");\n" .
			"} elseif (is_numeric({$inputExpression})) {\n" .
			"\t\$datetimeStringReturn = (new " . $this->dateTimeClass . "('@' . intval({$inputExpression})))->format(" . Helpers::dump($this->format) . ");\n" .
			"} elseif (is_string({$inputExpression})) {\n" .
			"\t\$datetimeStringReturn = (new " . $this->dateTimeClass . "({$inputExpression}))->format(" . Helpers::dump($this->format) . ");\n" .
			"} elseif (is_array({$inputExpression}) && isset({$inputExpression}['date'])) {\n" .
			"\t\$datetimeStringReturn = (new " . $this->dateTimeClass . "({$inputExpression}['date']))->format(" . Helpers::dump($this->format) . ");\n" .
			"} else {\n" .
			"\tthrow new \\InvalidArgumentException('Could not serialize date of format ' . " . Helpers::dump($this->format) . " . '.');\n" .
			"}",
			"\$datetimeStringReturn"
		);
	}

	public function deserialize(Property $property, $group, $inputExpression)
	{
		return StatementAndExpressionVO::withStatementAndExpression(
			"if ({$inputExpression} instanceof \\DateTimeInterface) {\n" .
			"\t\$datetimeInstanceReturn = {$inputExpression};\n" .
			"} elseif (is_numeric({$inputExpression})) {\n" .
			"\t\$datetimeInstanceReturn = new " . $this->dateTimeClass . "('@' . intval({$inputExpression}));\n" .
			"} elseif (is_string({$inputExpression})) {\n" .
			"\tif ({$inputExpression} === " . Helpers::dump($this->emptyValue) . ") {\n" .
			"\t\t\$datetimeInstanceReturn = null;\n" .
			"\t} else {\n" .
			"\t\t\$datetimeInstanceReturn = " . $this->dateTimeClass . "::createFromFormat(" . Helpers::dump($this->format) . ", {$inputExpression});\n" .
			"\t}\n" .
			"} elseif (is_array({$inputExpression}) && isset({$inputExpression}['date'])) {\n" .
			"\t\$datetimeInstanceReturn = new " . $this->dateTimeClass . "({$inputExpression}['date']);\n" .
			"} elseif ({$inputExpression} === null) {\n" .
			"\t\$datetimeInstanceReturn = null;\n" .
			"} else {\n" .
			"\tthrow new \\InvalidArgumentException('Could not deserialize date of format ' . " . Helpers::dump($this->format) . " . '.');\n" .
			"}",
			"\$datetimeInstanceReturn"
		);
	}

}
