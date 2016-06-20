<?php
namespace Skrz\Meta\PHP;

use Nette\PhpGenerator\ClassType;
use Nette\Utils\Strings;
use Skrz\Meta\AbstractMetaSpec;
use Skrz\Meta\AbstractModule;
use Skrz\Meta\MetaException;
use Skrz\Meta\MetaSpecMatcher;
use Skrz\Meta\PropertySerializerInterface;
use Skrz\Meta\Reflection\ArrayType;
use Skrz\Meta\Reflection\Property;
use Skrz\Meta\Reflection\ScalarType;
use Skrz\Meta\Reflection\Type;

class PhpModule extends AbstractModule
{

	/** @var string[] */
	private $defaultGroups = array(null);

	/** @var PropertySerializerInterface[] */
	private $propertySerializers = array();

	public function addDefaultGroup($group)
	{
		if (!in_array($group, $this->defaultGroups, true)) {
			$this->defaultGroups[] = $group;
		}

		return $this;
	}

	public function addPropertySerializer(PropertySerializerInterface $propertySerializer)
	{
		$this->propertySerializers[] = $propertySerializer;
		return $this;
	}

	public function onAdd(AbstractMetaSpec $spec, MetaSpecMatcher $matcher)
	{
		// nothing to do
	}

	public function onBeforeGenerate(AbstractMetaSpec $spec, MetaSpecMatcher $matcher, Type $type)
	{
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

			if (get_class($property->getType()) === "Skrz\\Meta\\Reflection\\MixedType") {
				throw new MetaException(
					"Property {$type->getName()}::\${$property->getName()} of type mixed. " .
					"Either add @var annotation with non-mixed type, " .
					"or mark it using @Transient annotation."
				);
			}

			$hasDefaultGroup = false;
			foreach ($property->getAnnotations("Skrz\\Meta\\PHP\\PhpArrayOffset") as $annotation) {
				/** @var PhpArrayOffset $annotation */
				if ($annotation->group === PhpArrayOffset::DEFAULT_GROUP) {
					$hasDefaultGroup = true;
				}

				if ($annotation->offset === null) {
					$annotation->offset = $property->getName();
				}
			}

			if (!$hasDefaultGroup) {
				$annotations = $property->getAnnotations();

				$annotations[] = $arrayOffset = new PhpArrayOffset();
				$arrayOffset->offset = $property->getName();

				$property->setAnnotations($annotations);
			}
		}
	}

	public function onGenerate(AbstractMetaSpec $spec, MetaSpecMatcher $matcher, Type $type, ClassType $class)
	{
		$groups = array();
		$inputOutputClasses = array($type->getName() => true);

		$i = 0;

		foreach ($this->defaultGroups as $defaultGroup) {
			$groups[$defaultGroup] = 1 << $i++;
		}

		$ns = $class->getNamespace();

		$ns->addUse("Skrz\\Meta\\PHP\\PhpMetaInterface");
		$ns->addUse($type->getName(), null, $typeAlias);
		$ns->addUse("Skrz\\Meta\\Stack", null, $stackAlias);
		$class->addImplement("Skrz\\Meta\\PHP\\PhpMetaInterface");

		// get groups
		foreach ($type->getProperties() as $property) {
			foreach ($property->getAnnotations("Skrz\\Meta\\PHP\\PhpArrayOffset") as $arrayOffset) {
				/** @var PhpArrayOffset $arrayOffset */
				if (!isset($groups[$arrayOffset->group])) {
					$groups[$arrayOffset->group] = 1 << $i++;
				}
			}
		}

		// get discriminator
		$discriminatorOffsetMap = array();
		$discriminatorClassMap = array();
		$discriminatorMetaMap = array();

		foreach ($type->getAnnotations("Skrz\\Meta\\PHP\\PhpDiscriminatorOffset") as $discriminatorOffset) {
			/** @var PhpDiscriminatorOffset $discriminatorOffset */

			if (!isset($groups[$discriminatorOffset->group])) {
				$groups[$discriminatorOffset->group] = 1 << $i++;
			}

			$discriminatorOffsetMap[$groups[$discriminatorOffset->group]] = $discriminatorOffset->offset;
		}

		foreach ($type->getAnnotations("Skrz\\Meta\\PHP\\PhpDiscriminatorMap") as $discriminatorMap) {
			/** @var PhpDiscriminatorMap $discriminatorMap */

			if (!isset($groups[$discriminatorMap->group])) {
				$groups[$discriminatorMap->group] = 1 << $i++;
			}

			if (isset($discriminatorMetaMap[$groups[$discriminatorMap->group]])) {
				throw new MetaException(
					"More @PhpDiscriminatorMap annotations with same group '{$discriminatorMap->group}'."
				);
			}

			$discriminatorClassMap[$groups[$discriminatorMap->group]] = array();
			$discriminatorMetaMap[$groups[$discriminatorMap->group]] = array();
			$currentClassMap =& $discriminatorClassMap[$groups[$discriminatorMap->group]];
			$currentMetaMap =& $discriminatorMetaMap[$groups[$discriminatorMap->group]];

			foreach ($discriminatorMap->map as $value => $className) {
				$currentClassMap[$value] = $className;
				$inputOutputClasses[$className] = true;
				$currentMetaMap[$value] = $spec->createMetaClassName(Type::fromString($className));
			}
		}

		// add groups property
		$groupsProperty = $class->addProperty("groups");
		$groupsProperty->setStatic(true)->setValue($groups)->setVisibility("private");
		$groupsProperty
			->addComment("Mapping from group name to group ID for fromArray() and toArray()")
			->addComment("")
			->addComment("@var string[]");

		// create input/output type hint
		$inputOutputTypeHint = array();
		$inputOutputClasses = array_keys($inputOutputClasses);
		sort($inputOutputClasses);
		foreach ($inputOutputClasses as $inputOutputClass) {
			$ns->addUse($inputOutputClass, null, $alias);
			$inputOutputTypeHint[] = $alias;
		}
		$inputOutputTypeHint = implode("|", $inputOutputTypeHint);

		foreach (array("Array", "Object") as $what) {
			// from*() method
			$from = $class->addMethod("from{$what}");
			$from->setStatic(true);
			$from->addParameter("input");
			$from->addParameter("group")->setOptional(true);
			$from->addParameter("object")->setOptional(true);

			$from
				->addComment("Creates \\{$type->getName()} object from " . strtolower($what))
				->addComment("")
				->addComment("@param " . strtolower($what) . " \$input")
				->addComment("@param string \$group")
				->addComment("@param {$inputOutputTypeHint} \$object")
				->addComment("")
				->addComment("@throws \\Exception")
				->addComment("")
				->addComment("@return {$inputOutputTypeHint}");

			if ($what === "Object") {
				$from->addBody("\$input = (array)\$input;\n");
			}

			// TODO: more groups - include/exclude
			$from
				->addBody("if (!isset(self::\$groups[\$group])) {")
				->addBody("\tthrow new \\InvalidArgumentException('Group \\'' . \$group . '\\' not supported for ' . " . var_export($type->getName(), true) . " . '.');")
				->addBody("} else {")
				->addBody("\t\$id = self::\$groups[\$group];")
				->addBody("}")
				->addBody("");

			if (!empty($discriminatorMetaMap)) {
				foreach ($discriminatorMetaMap as $groupId => $groupDiscriminatorMetaMap) {
					if (isset($discriminatorOffsetMap[$groupId])) {
						$groupDiscriminatorOffset = $discriminatorOffsetMap[$groupId];

						foreach ($groupDiscriminatorMetaMap as $value => $metaClass) {
							$ns->addUse($metaClass, null, $alias);
							$from
								->addBody(
									"if ((\$id & {$groupId}) > 0 && " .
									"isset(\$input[" . var_export($groupDiscriminatorOffset, true) . "]) && " .
									"\$input[" . var_export($groupDiscriminatorOffset, true) . "] === " . var_export($value, true) . ") {"
								)
								->addBody("\treturn {$alias}::from{$what}(\$input, \$group, \$object);")
								->addBody("}")
								->addBody("");
						}
					} else {
						foreach ($groupDiscriminatorMetaMap as $value => $metaClass) {
							$ns->addUse($metaClass, null, $alias);
							$from
								->addBody(
									"if ((\$id & {$groupId}) > 0 && " .
									"isset(\$input[" . var_export($value, true) . "])) {"
								)
								->addBody("\treturn {$alias}::from{$what}(\$input[" . var_export($value, true) . "], \$group, \$object);")
								->addBody("}")
								->addBody("");
						}
					}
				}
			}

			$from
				->addBody("if (\$object === null) {")
				->addBody("\t\$object = new {$typeAlias}();")
				->addBody("} elseif (!(\$object instanceof {$typeAlias})) {")
				->addBody("\tthrow new \\InvalidArgumentException('You have to pass object of class {$type->getName()}.');")
				->addBody("}")
				->addBody("");

			foreach ($type->getProperties() as $property) {
				foreach ($property->getAnnotations("Skrz\\Meta\\PHP\\PhpArrayOffset") as $arrayOffset) {
					/** @var PhpArrayOffset $arrayOffset */
					$groupId = $groups[$arrayOffset->group];
					$arrayKey = var_export($arrayOffset->offset, true);
					$baseArrayPath = $arrayPath = "\$input[{$arrayKey}]";
					$baseObjectPath = $objectPath = "\$object->{$property->getName()}";
					$from->addBody("if ((\$id & {$groupId}) > 0 && isset({$arrayPath})) {"); // FIXME: group group IDs by offset

					$baseType = $property->getType();
					$indent = "\t";
					$before = "";
					$after = "";
					for ($i = 0; $baseType instanceof ArrayType; ++$i) {
						$arrayType = $baseType;
						$baseType = $arrayType->getBaseType();


						$before .= "{$indent}if (!(isset({$objectPath}) && is_array({$objectPath}))) {\n";
						$before .= "{$indent}\t{$objectPath} = array();\n";
						$before .= "{$indent}}\n";
						$before .= "{$indent}foreach ({$arrayPath} instanceof \\Traversable ? {$arrayPath} : (array){$arrayPath} as \$k{$i} => \$v{$i}) {\n";
						$after = "{$indent}}\n" . $after;
						$indent .= "\t";
						$arrayPath = "\$v{$i}";
						$objectPath .= "[\$k{$i}]";
					}

					if (!empty($before)) {
						$from->addBody(rtrim($before));
					}

					$matchingPropertySerializer = $this->getMatchingPropertySerializer($property, $arrayOffset);

					if ($matchingPropertySerializer !== null) {
						$sevo = $matchingPropertySerializer->deserialize($property, $arrayOffset->group, $arrayPath);
						if ($sevo->getStatement()) {
							$from->addBody(Strings::indent($sevo->getStatement(), 1, $indent));
						}
						$from->addBody("{$indent}{$objectPath} = {$sevo->getExpression()};");

					} elseif ($baseType instanceof ScalarType) {
						$from->addBody("{$indent}{$objectPath} = {$arrayPath};");

					} elseif ($baseType instanceof Type) {
						$propertyTypeMetaClassName = $spec->createMetaClassName($baseType);
						$ns->addUse($propertyTypeMetaClassName, null, $propertyTypeMetaClassNameAlias);
						$from->addBody(
							"{$indent}{$objectPath} = {$propertyTypeMetaClassNameAlias}::from{$what}(" .
							"{$arrayPath}, " .
							"\$group, " .
							"isset({$objectPath}) ? {$objectPath} : null" .
							");"
						);

					} else {
						throw new MetaException("Unsupported property type " . get_class($baseType) . " ({$type->getName()}::\${$property->getName()}).");
					}

					if (!empty($after)) {
						$from->addBody(rtrim($after));
					}

					$from
						->addBody("} elseif ((\$id & {$groupId}) > 0 && array_key_exists({$arrayKey}, \$input) && {$baseArrayPath} === null) {")
						->addBody("\t{$baseObjectPath} = null;")
						->addBody("}");
				}

				$from->addBody("");
			}

			$from->addBody("return \$object;");

			// to*() method
			$to = $class->addMethod("to{$what}");
			$to->setStatic(true);
			$to->addParameter("object");
			$to->addParameter("group")->setOptional(true);
			$to->addParameter("filter")->setOptional(true);

			$to
				->addComment("Serializes \\{$type->getName()} to " . strtolower($what))
				->addComment("")
				->addComment("@param {$inputOutputTypeHint} \$object")
				->addComment("@param string \$group")
				->addComment("@param array \$filter")
				->addComment("")
				->addComment("@throws \\Exception")
				->addComment("")
				->addComment("@return " . strtolower($what));

			$to
				->addBody("if (\$object === null) {")
				->addBody("\treturn null;")
				->addBody("}");

			// TODO: more groups - include/exclude
			$to
				->addBody("if (!isset(self::\$groups[\$group])) {")
				->addBody("\tthrow new \\InvalidArgumentException('Group \\'' . \$group . '\\' not supported for ' . " . var_export($type->getName(), true) . " . '.');")
				->addBody("} else {")
				->addBody("\t\$id = self::\$groups[\$group];")
				->addBody("}")
				->addBody("");

			if (!empty($discriminatorClassMap)) {
				foreach ($discriminatorClassMap as $groupId => $groupDiscriminatorClassMap) {
					$groupDiscriminatorOffset = null;
					if (isset($discriminatorOffsetMap[$groupId])) {
						$groupDiscriminatorOffset = $discriminatorOffsetMap[$groupId];
					}

					foreach ($groupDiscriminatorClassMap as $value => $className) {
						$metaClassName = $discriminatorMetaMap[$groupId][$value];
						$ns->addUse($className, null, $alias);
						$ns->addUse($metaClassName, null, $metaAlias);

						$to
							->addBody("if ((\$id & {$groupId}) > 0 && \$object instanceof {$alias}) {")
							->addBody("\t\$output = {$metaAlias}::to{$what}(\$object, \$group);");

						if ($groupDiscriminatorOffset === null) {
							$to->addBody("\t\$output = " . ($what === "Object" ? "(object)" : "") . "array(" . var_export($value, true) . " => " . ($what === "Object" ? "(object)" : "") . "\$output);");
						} else {
							if ($what === "Object") {
								$to->addBody("\t\$output->{$groupDiscriminatorOffset} = " . var_export($value, true) . ";"); // FIXME: might compile to incorrect PHP code
							} else {
								$to->addBody("\t\$output[" . var_export($groupDiscriminatorOffset, true) . "] = " . var_export($value, true) . ";");
							}
						}

						$to
							->addBody("\treturn \$output;")
							->addBody("}")
							->addBody("");
					}
				}
			}

			$to
				->addBody("if (!(\$object instanceof {$typeAlias})) {")
				->addBody("\tthrow new \\InvalidArgumentException('You have to pass object of class {$type->getName()}.');")
				->addBody("}")
				->addBody("");

			$to
				->addBody("if ({$stackAlias}::\$objects === null) {")
				->addBody("\t{$stackAlias}::\$objects = new \\SplObjectStorage();")
				->addBody("}")
				->addBody("")
				->addBody("if ({$stackAlias}::\$objects->contains(\$object)) {")
				->addBody("\treturn null;")
				->addBody("}")
				->addBody("")
				->addBody("{$stackAlias}::\$objects->attach(\$object);")
				->addBody("");

			$to
				->addBody("try {")
				->addBody("\t\$output = array();")
				->addBody("");

			foreach ($type->getProperties() as $property) {
				$propertyGroups = [];

				foreach ($property->getAnnotations("Skrz\\Meta\\PHP\\PhpArrayOffset") as $arrayOffset) {
					if (isset($propertyGroups[$arrayOffset->group])) {
						continue;
					}

					$propertyGroups[$arrayOffset->group] = true;

					/** @var PhpArrayOffset $arrayOffset */
					$groupId = $groups[$arrayOffset->group];
					$if = "\tif ((\$id & {$groupId}) > 0";
					if ($arrayOffset->ignoreNull) {
						$if .= " && ((isset(\$object->{$property->getName()}) && \$filter === null)";
					} else {
						$if .= " && (\$filter === null";
					}
					$if .= " || isset(\$filter[" . var_export($arrayOffset->offset, true) . "]))) {"; // FIXME: group group IDs by offset
					$to->addBody($if);

					$objectPath = "\$object->{$property->getName()}";
					$arrayPath = "\$output[" . var_export($arrayOffset->offset, true) . "]";
					$baseType = $property->getType();
					$indent = "\t\t";
					$before = "";
					$after = "";
					for ($i = 0; $baseType instanceof ArrayType; ++$i) {
						$arrayType = $baseType;
						$baseType = $arrayType->getBaseType();

						$before .= "{$indent}if (!(isset({$arrayPath}) && is_array({$arrayPath}))) {\n";
						$before .= "{$indent}\t{$arrayPath} = array();\n";
						$before .= "{$indent}}\n";
						$before .= "{$indent}foreach ({$objectPath} instanceof \\Traversable ? {$objectPath} : (array){$objectPath} as \$k{$i} => \$v{$i}) {\n";
						$after = "{$indent}}\n" . $after;
						$indent .= "\t";
						$arrayPath .= "[\$k{$i}]";
						$objectPath = "\$v{$i}";
					}

					if (!empty($before)) {
						$to->addBody(rtrim($before));
					}

					$matchingPropertySerializer = $this->getMatchingPropertySerializer($property, $arrayOffset);

					if ($matchingPropertySerializer !== null) {
						$sevo = $matchingPropertySerializer->serialize($property, $arrayOffset->group, $objectPath);
						if ($sevo->getStatement()) {
							$to->addBody(Strings::indent($sevo->getStatement(), 1, $indent));
						}
						$to->addBody("{$indent}{$arrayPath} = {$sevo->getExpression()};");

					} elseif ($baseType instanceof ScalarType) {
						$to->addBody("{$indent}{$arrayPath} = {$objectPath};");

					} elseif ($baseType instanceof Type) {
						$propertyTypeMetaClassName = $spec->createMetaClassName($baseType);
						$ns->addUse($propertyTypeMetaClassName, null, $propertyTypeMetaClassNameAlias);
						$to->addBody(
							"{$indent}{$arrayPath} = {$propertyTypeMetaClassNameAlias}::to{$what}(" .
							"{$objectPath}, " .
							"\$group, " .
							"\$filter === null ? null : \$filter[" . var_export($arrayOffset->offset, true) . "]" .
							");"
						);

					} else {
						throw new MetaException("Unsupported property type " . get_class($baseType) . ".");
					}

					if (!empty($after)) {
						$to->addBody(rtrim($after));
					}

					$to->addBody("\t}");
				}

				$to->addBody("");
			}

			$to
				->addBody("} catch (\\Exception \$e) {")
				->addBody("\t{$stackAlias}::\$objects->detach(\$object);")
				->addBody("\tthrow \$e;")
				->addBody("}")
				->addBody("");

			$to
				->addBody("{$stackAlias}::\$objects->detach(\$object);")
				->addBody("return " . ($what === "Object" ? "(object)" : "") . "\$output;");
		}
	}

	public function getMatchingPropertySerializer(Property $property, PhpArrayOffset $arrayOffset)
	{
		$matchingPropertySerializer = null;
		foreach ($this->propertySerializers as $propertySerializer) {
			if ($propertySerializer->matchesSerialize($property, $arrayOffset->group)) {
				$matchingPropertySerializer = $propertySerializer;
				break;
			}
		}

		return $matchingPropertySerializer;
	}

}
