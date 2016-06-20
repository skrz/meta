<?php
namespace Skrz\Meta\Protobuf;

use Nette\PhpGenerator\ClassType;
use Skrz\Meta\AbstractMetaSpec;
use Skrz\Meta\AbstractModule;
use Skrz\Meta\MetaException;
use Skrz\Meta\MetaSpecMatcher;
use Skrz\Meta\Reflection\ArrayType;
use Skrz\Meta\Reflection\BoolType;
use Skrz\Meta\Reflection\FloatType;
use Skrz\Meta\Reflection\IntType;
use Skrz\Meta\Reflection\NumericType;
use Skrz\Meta\Reflection\ObjectType;
use Skrz\Meta\Reflection\ScalarType;
use Skrz\Meta\Reflection\StringType;
use Skrz\Meta\Reflection\Type;

class ProtobufModule extends AbstractModule
{

	public function onAdd(AbstractMetaSpec $spec, MetaSpecMatcher $matcher)
	{
		// nothing
	}

	public function onBeforeGenerate(AbstractMetaSpec $spec, MetaSpecMatcher $matcher, Type $type)
	{
		$lastNumber = 0;
		$seenNumbers = [];

		foreach ($type->getProperties() as $property) {
			if ($property->hasAnnotation("Skrz\\Meta\\Transient")) {
				continue;
			}

			if ($property->isPrivate()) {
				throw new MetaException(
					"Private property '{$type->getName()}::\${$property->getName()}'. " .
					"Either make the property protected/public if you need to process it, " .
					"or mark it using @Transient annotation."
				);
			}

			$propertyType = $property->getType();

			if (get_class($propertyType) === "Skrz\\Meta\\Reflection\\MixedType") {
				throw new MetaException(
					"Property {$type->getName()}::\${$property->getName()} of type mixed. " .
					"Either add @var annotation with non-mixed type, " .
					"or mark it using @Transient annotation."
				);
			}

			/** @var ProtobufField $field */
			$field = $property->getAnnotation("Skrz\\Meta\\Protobuf\\ProtobufField");

			if ($field) {
				if (!$field->number) {
					$field->number = ++$lastNumber;
				} else {
					$lastNumber = $field->number;
				}

			} else {
				$field = new ProtobufField();
				$field->number = ++$lastNumber;

				$annotations = $property->getAnnotations();
				$annotations[] = $field;
				$property->setAnnotations($annotations);
			}

			if (isset($seenNumbers[$field->number])) {
				throw new MetaException(
					"Property {$type->getName()}::\${$property->getName()} has duplicate field number #{$field->number}. " .
					"Already seen at property {$type->getName()}::\${$seenNumbers[$field->number]}."
				);
			}

			$seenNumbers[$field->number] = $property->getName();

			if (!$field->wireType) {
				$baseType = $propertyType;
				if ($baseType instanceof ArrayType) {
					$baseType = $baseType->getBaseType();

					if (!($baseType instanceof ScalarType || $baseType instanceof ObjectType)) {
						throw new MetaException(
							"Property {$type->getName()}::\${$property->getName()} of type " . ((string)$propertyType) .
							" cannot be processed by Protobufs. Array base type has to be scalar or object type," .
							" currently is " . ((string)$baseType) . "."
						);
					}
				}

				if ($baseType instanceof FloatType) { // must be before NumericType!
					$field->wireType = WireTypeEnum::FIXED64;
				} elseif ($baseType instanceof BoolType || $baseType instanceof NumericType) {
					$field->wireType = WireTypeEnum::VARINT;
				} elseif ($baseType instanceof StringType || $baseType instanceof Type) {
					$field->wireType = WireTypeEnum::STRING;
				} else {
					throw new MetaException(
						"Unhandled type " . ((string)$baseType) . ", property {$type->getName()}::\${$property->getName()}."
					);
				}
			}

			if ($field->packed && !$propertyType->isArray()) {
				throw new MetaException(
					"Only array properties can be packed ({$type->getName()}::\${$property->getName()})."
				);
			}
		}
	}

	public function onGenerate(AbstractMetaSpec $spec, MetaSpecMatcher $matcher, Type $type, ClassType $class)
	{
		$ns = $class->getNamespace();

		$ns->addUse("Skrz\\Meta\\Protobuf\\ProtobufMetaInterface");
		$ns->addUse($type->getName(), null, $typeAlias);
		$ns->addUse("Skrz\\Meta\\Protobuf\\Binary", null, $binary);
		$ns->addUse("Skrz\\Meta\\Protobuf\\ProtobufException", null, $protobufExceptionAlias);

		$class->addImplement("Skrz\\Meta\\Protobuf\\ProtobufMetaInterface");

		foreach ($type->getProperties() as $property) {
			if ($property->hasAnnotation("Skrz\\Meta\\Transient")) {
				continue;
			}

			/** @var ProtobufField $field */
			$field = $property->getAnnotation("Skrz\\Meta\\Protobuf\\ProtobufField");

			$class->addConst(
				strtoupper(trim(preg_replace("/([A-Z])/", "_\$1", $property->getName() . "ProtobufField"), "_")),
				$field->number
			);
		}

		$fromProtobuf = $class->addMethod("fromProtobuf");
		$fromProtobuf->setStatic(true);
		$fromProtobuf->addParameter("input");
		$fromProtobuf->addParameter("object", null)->setOptional(true);
		$fromProtobuf->addParameter("start", 0)->setReference(true)->setOptional(true);
		$fromProtobuf->addParameter("end", null)->setOptional(true);
		$fromProtobuf
			->addComment("Creates \\{$type->getName()} object from serialized Protocol Buffers message.")
			->addComment("")
			->addComment("@param string \$input")
			->addComment("@param {$typeAlias} \$object")
			->addComment("@param int \$start")
			->addComment("@param int \$end")
			->addComment("")
			->addComment("@throws \\Exception")
			->addComment("")
			->addComment("@return {$typeAlias}");

		$fromProtobuf
			->addBody("if (\$object === null) {")
			->addBody("\t\$object = new {$typeAlias}();")
			->addBody("}")
			->addBody("")
			->addBody("if (\$end === null) {")
			->addBody("\t\$end = strlen(\$input);")
			->addBody("}")
			->addBody("");

		$fromProtobuf->addBody("while (\$start < \$end) {");

		$fromProtobuf->addBody("\t\$tag = {$binary}::decodeVarint(\$input, \$start);");
		$fromProtobuf->addBody("\t\$wireType = \$tag & 0x7;");
		$fromProtobuf->addBody("\t\$number = \$tag >> 3;");

		$fromProtobuf->addBody("\tswitch (\$number) {");

		foreach ($type->getProperties() as $property) {
			if ($property->hasAnnotation("Skrz\\Meta\\Transient")) {
				continue;
			}

			$propertyType = $property->getType();
			$baseType = $propertyType;
			if ($baseType instanceof ArrayType) {
				$baseType = $baseType->getBaseType();
			}

			/** @var ProtobufField $field */
			$field = $property->getAnnotation("Skrz\\Meta\\Protobuf\\ProtobufField");

			$fromProtobuf->addBody("\t\tcase {$field->number}:");

			if ($field->packed) {
				$binaryWireType = WireTypeEnum::toBinaryWireType(WireTypeEnum::STRING);
			} else {
				$binaryWireType = WireTypeEnum::toBinaryWireType($field->wireType);
			}

			$fromProtobuf->addBody("\t\t\tif (\$wireType !== {$binaryWireType}) {");
			$fromProtobuf->addBody("\t\t\t\tthrow new {$protobufExceptionAlias}('Unexpected wire type ' . \$wireType . ', expected {$binaryWireType}.', \$number);");
			$fromProtobuf->addBody("\t\t\t}");

			$propertyLhs = "\$object->{$property->getName()}";
			if ($propertyType->isArray()) {
				$fromProtobuf->addBody("\t\t\tif (!(isset({$propertyLhs}) && is_array({$propertyLhs}))) {");
				$fromProtobuf->addBody("\t\t\t\t{$propertyLhs} = array();");
				$fromProtobuf->addBody("\t\t\t}");
				$propertyLhs .= "[]";
			}

			if ($field->packed) {
				$fromProtobuf
					->addBody("\t\t\t\$packedLength = {$binary}::decodeVarint(\$input, \$start);")
					->addBody("\t\t\t\$expectedPacked = \$start + \$packedLength;")
					->addBody("\t\t\tif (\$expectedPacked > \$end) {")
					->addBody("\t\t\t\tthrow new {$protobufExceptionAlias}('Not enough data.');")
					->addBody("\t\t\t}")
					->addBody("\t\t\twhile (\$start < \$expectedPacked) {");
				$indent = "\t\t\t\t";
			} else {
				$indent = "\t\t\t";
			}

			switch ($field->wireType) {
				case WireTypeEnum::VARINT:
					$fromProtobuf->addBody("{$indent}{$propertyLhs} = " . ($baseType instanceof BoolType ? "(bool)" : "") ."{$binary}::decodeVarint(\$input, \$start);");
					break;

				case WireTypeEnum::ZIGZAG:
					$fromProtobuf->addBody("{$indent}{$propertyLhs} = {$binary}::decodeZigzag(\$input, \$start);");
					break;

				case WireTypeEnum::FIXED64:
					$fromProtobuf
						->addBody("{$indent}\$expectedStart = \$start + 8;")
						->addBody("{$indent}if (\$expectedStart > \$end) {")
						->addBody("{$indent}\tthrow new {$protobufExceptionAlias}('Not enough data.');")
						->addBody("{$indent}}");

					if ($baseType instanceof FloatType) {
						$fromProtobuf->addBody("{$indent}{$propertyLhs} = {$binary}::decodeDouble(\$input, \$start);");
					} elseif ($baseType instanceof IntType && $field->unsigned) {
						$fromProtobuf->addBody("{$indent}{$propertyLhs} = {$binary}::decodeUint64(\$input, \$start);");
					} elseif ($baseType instanceof IntType && !$field->unsigned) {
						$fromProtobuf->addBody("{$indent}{$propertyLhs} = {$binary}::decodeInt64(\$input, \$start);");
					} else {
						throw new MetaException(
							"Property {$type->getName()}::\${$property->getName()} has wire type '{$field->wireType}' and base type {$baseType}. " .
							"'{$field->wireType}' supports only float or int base types."
						);
					}

					$fromProtobuf
						->addBody("{$indent}if (\$start !== \$expectedStart) {")
						->addBody("{$indent}\tthrow new {$protobufExceptionAlias}('Unexpected start. Expected ' . \$expectedStart . ', got ' . \$start . '.', \$number);")
						->addBody("{$indent}}");
					break;

				case WireTypeEnum::FIXED32:
					$fromProtobuf
						->addBody("{$indent}\$expectedStart = \$start + 4;")
						->addBody("{$indent}if (\$expectedStart > \$end) {")
						->addBody("{$indent}\tthrow new {$protobufExceptionAlias}('Not enough data.');")
						->addBody("{$indent}}");

					if ($baseType instanceof FloatType) {
						$fromProtobuf->addBody("{$indent}{$propertyLhs} = {$binary}::decodeFloat(\$input, \$start);");
					} elseif ($baseType instanceof IntType && $field->unsigned) {
						$fromProtobuf->addBody("{$indent}{$propertyLhs} = {$binary}::decodeUint32(\$input, \$start);");
					} elseif ($baseType instanceof IntType && !$field->unsigned) {
						$fromProtobuf->addBody("{$indent}{$propertyLhs} = {$binary}::decodeInt32(\$input, \$start);");
					} else {
						throw new MetaException(
							"Property {$type->getName()}::\${$property->getName()} has wire type '{$field->wireType}' and base type {$baseType}. " .
							"'{$field->wireType}' supports only float or int base types."
						);
					}

					$fromProtobuf
						->addBody("{$indent}if (\$start !== \$expectedStart) {")
						->addBody("{$indent}\tthrow new {$protobufExceptionAlias}('Unexpected start. Expected ' . \$expectedStart . ', got ' . \$start . '.', \$number);")
						->addBody("{$indent}}");
					break;

				case WireTypeEnum::STRING:
					$fromProtobuf
						->addBody("{$indent}\$length = {$binary}::decodeVarint(\$input, \$start);")
						->addBody("{$indent}\$expectedStart = \$start + \$length;")
						->addBody("{$indent}if (\$expectedStart > \$end) {")
						->addBody("{$indent}\tthrow new {$protobufExceptionAlias}('Not enough data.');")
						->addBody("{$indent}}");

					if ($baseType instanceof StringType) {
						$fromProtobuf->addBody("{$indent}{$propertyLhs} = substr(\$input, \$start, \$length);");
						$fromProtobuf->addBody("{$indent}\$start += \$length;");

					} elseif ($baseType instanceof Type) {
						$propertyTypeMetaClassName = $spec->createMetaClassName($baseType);
						$ns->addUse($propertyTypeMetaClassName, null, $propertyTypeMetaClassNameAlias);
						$fromProtobuf->addBody("{$indent}{$propertyLhs} = {$propertyTypeMetaClassNameAlias}::fromProtobuf(\$input, null, \$start, \$start + \$length);");

					} else {
						throw new MetaException(
							"Property {$type->getName()}::\${$property->getName()} has wire type '{$field->wireType}' and base type {$baseType}. " .
							"'{$field->wireType}' supports only string or object types."
						);
					}

					$fromProtobuf
						->addBody("{$indent}if (\$start !== \$expectedStart) {")
						->addBody("{$indent}\tthrow new {$protobufExceptionAlias}('Unexpected start. Expected ' . \$expectedStart . ', got ' . \$start . '.', \$number);")
						->addBody("{$indent}}");
					break;

				default:
					throw new \InvalidArgumentException("Unhandled field wire type '{$field->wireType}'.");
			}

			if ($field->packed) {
				$fromProtobuf
					->addBody("\t\t\t}")
					->addBody("\t\t\tif (\$start !== \$expectedPacked) {")
					->addBody("\t\t\t\tthrow new {$protobufExceptionAlias}('Unexpected start. Expected ' . \$expectedPacked . ', got ' . \$start . '.', \$number);")
					->addBody("\t\t\t}");
			}

			$fromProtobuf->addBody("\t\t\tbreak;");
		}

		$fromProtobuf
			->addBody("\t\tdefault:")
			->addBody("\t\t\tswitch (\$wireType) {")
			->addBody("\t\t\t\tcase " . WireTypeEnum::toBinaryWireType(WireTypeEnum::VARINT) . ":")
			->addBody("\t\t\t\t\t{$binary}::decodeVarint(\$input, \$start);")
			->addBody("\t\t\t\t\tbreak;")
			->addBody("\t\t\t\tcase " . WireTypeEnum::toBinaryWireType(WireTypeEnum::FIXED64) . ":")
			->addBody("\t\t\t\t\t\$start += 8;")
			->addBody("\t\t\t\t\tbreak;")
			->addBody("\t\t\t\tcase " . WireTypeEnum::toBinaryWireType(WireTypeEnum::STRING) . ":")
			->addBody("\t\t\t\t\t\$start += {$binary}::decodeVarint(\$input, \$start);")
			->addBody("\t\t\t\t\tbreak;")
			->addBody("\t\t\t\tcase " . WireTypeEnum::toBinaryWireType(WireTypeEnum::FIXED32) . ":")
			->addBody("\t\t\t\t\t\$start += 4;")
			->addBody("\t\t\t\t\tbreak;")
			->addBody("\t\t\t\tdefault:")
			->addBody("\t\t\t\t\tthrow new {$protobufExceptionAlias}('Unexpected wire type ' . \$wireType . '.', \$number);")
			->addBody("\t\t\t}");

		$fromProtobuf->addBody("\t}");

		$fromProtobuf->addBody("}");
		$fromProtobuf->addBody("")->addBody("return \$object;");

		$toProtobuf = $class->addMethod("toProtobuf");
		$toProtobuf->setStatic(true);
		$toProtobuf->addParameter("object");
		$toProtobuf->addParameter("filter", null);
		$toProtobuf
			->addComment("Serialized \\{$type->getName()} to Protocol Buffers message.")
			->addComment("")
			->addComment("@param {$typeAlias} \$object")
			->addComment("@param array \$filter")
			->addComment("")
			->addComment("@throws \\Exception")
			->addComment("")
			->addComment("@return string");

		$toProtobuf->addBody("\$output = '';")->addBody("");


		foreach ($type->getProperties() as $property) {
			if ($property->hasAnnotation("Skrz\\Meta\\Transient")) {
				continue;
			}

			$propertyType = $property->getType();
			$baseType = $propertyType;
			if ($baseType instanceof ArrayType) {
				$baseType = $baseType->getBaseType();
			}

			/** @var ProtobufField $field */
			$field = $property->getAnnotation("Skrz\\Meta\\Protobuf\\ProtobufField");

			$toProtobuf->addBody("if (isset(\$object->{$property->getName()}) && (\$filter === null || isset(\$filter[" . var_export($property->getName(), true) . "]))) {");

			$var = "\$object->{$property->getName()}";
			$output = "\$output";
			$indent = "\t";

			if ($field->packed) {
				$toProtobuf
					->addBody("\t\$packedBuffer = '';")
					->addBody(
						"\t\$output .= \"" .
						implode("", array_map(function ($s) {
							return "\\x" . $s;
						}, str_split(bin2hex(Binary::encodeVarint($field->number << 3 | WireTypeEnum::toBinaryWireType(WireTypeEnum::STRING))), 2))) .
						"\";"
					);
				$output = "\$packedBuffer";
			}

			if ($propertyType->isArray()) {
				$toProtobuf->addBody("\tforeach ({$var} instanceof \\Traversable ? {$var} : (array){$var} as \$k => \$v) {");
				$var = "\$v";
				$indent .= "\t";
			}

			if (!$field->packed) {
				$toProtobuf->addBody(
					"{$indent}{$output} .= \"" .
					implode("", array_map(function ($s) {
						return "\\x" . $s;
					}, str_split(bin2hex(Binary::encodeVarint($field->number << 3 | WireTypeEnum::toBinaryWireType($field->wireType))), 2))) .
					"\";"
				);
			}

			switch ($field->wireType) {
				case WireTypeEnum::VARINT:
					$toProtobuf->addBody("{$indent}{$output} .= {$binary}::encodeVarint(" . ($baseType instanceof BoolType ? "(int)" : "") ."{$var});");
					break;
				case WireTypeEnum::ZIGZAG:
					$toProtobuf->addBody("{$indent}{$output} .= {$binary}::encodeZigzag({$var});");
					break;
				case WireTypeEnum::FIXED64:
					if ($baseType instanceof FloatType) {
						$toProtobuf->addBody("{$indent}{$output} .= {$binary}::encodeDouble({$var});");
					} elseif ($baseType instanceof IntType && $field->unsigned) {
						$toProtobuf->addBody("{$indent}{$output} .= {$binary}::encodeUint64({$var});");
					} elseif ($baseType instanceof IntType && !$field->unsigned) {
						$toProtobuf->addBody("{$indent}{$output} .= {$binary}::encodeInt64({$var});");
					} else {
						throw new MetaException(
							"Property {$type->getName()}::\${$property->getName()} has wire type '{$field->wireType}' and base type {$baseType}. " .
							"'{$field->wireType}' supports only float or int base types."
						);
					}
					break;
				case WireTypeEnum::STRING:
					if ($baseType instanceof StringType) {
						$toProtobuf->addBody("{$indent}{$output} .= {$binary}::encodeVarint(strlen({$var}));");
						$toProtobuf->addBody("{$indent}{$output} .= {$var};");
					} elseif ($baseType instanceof Type) {
						$propertyTypeMetaClassName = $spec->createMetaClassName($baseType);
						$ns->addUse($propertyTypeMetaClassName, null, $propertyTypeMetaClassNameAlias);
						$toProtobuf->addBody("{$indent}\$buffer = {$propertyTypeMetaClassNameAlias}::toProtobuf({$var}, \$filter === null ? null : \$filter[" . var_export($property->getName(), true) . "]);");
						$toProtobuf->addBody("{$indent}{$output} .= {$binary}::encodeVarint(strlen(\$buffer));");
						$toProtobuf->addBody("{$indent}{$output} .= \$buffer;");

					} else {
						throw new MetaException(
							"Property {$type->getName()}::\${$property->getName()} has wire type '{$field->wireType}' and base type {$baseType}. " .
							"'{$field->wireType}' supports only string or object types."
						);
					}
					break;
				case WireTypeEnum::FIXED32:
					if ($baseType instanceof FloatType) {
						$toProtobuf->addBody("{$indent}{$output} .= {$binary}::encodeFloat({$var});");
					} elseif ($baseType instanceof IntType && $field->unsigned) {
						$toProtobuf->addBody("{$indent}{$output} .= {$binary}::encodeUint32({$var});");
					} elseif ($baseType instanceof IntType && !$field->unsigned) {
						$toProtobuf->addBody("{$indent}{$output} .= {$binary}::encodeInt32({$var});");
					} else {
						throw new MetaException(
							"Property {$type->getName()}::\${$property->getName()} has wire type '{$field->wireType}' and base type {$baseType}. " .
							"'{$field->wireType}' supports only float or int base types."
						);
					}
					break;
			}

			if ($propertyType->isArray()) {
				$toProtobuf->addBody("\t}");
			}

			if ($field->packed) {
				$toProtobuf->addBody("\t\$output .= {$binary}::encodeVarint(strlen(\$packedBuffer));");
				$toProtobuf->addBody("\t\$output .= \$packedBuffer;");
			}

			$toProtobuf->addBody("}")->addBody("");
		}

		$toProtobuf->addBody("return \$output;");
	}

}
