<?php
namespace Skrz\Meta\JSON;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Helpers;
use Skrz\Meta\AbstractMetaSpec;
use Skrz\Meta\AbstractModule;
use Skrz\Meta\MetaException;
use Skrz\Meta\MetaSpecMatcher;
use Skrz\Meta\PHP\PhpArrayOffset;
use Skrz\Meta\PHP\PhpDiscriminatorMap;
use Skrz\Meta\PHP\PhpDiscriminatorOffset;
use Skrz\Meta\PHP\PhpModule;
use Skrz\Meta\Reflection\ScalarType;
use Skrz\Meta\Reflection\Type;
use Skrz\Meta\Transient;

class JsonModule extends AbstractModule
{

	/** @var PhpModule */
	private $phpModule;

	public function onAdd(AbstractMetaSpec $spec, MetaSpecMatcher $matcher)
	{
		// depends on PhpModule
		$present = false;
		foreach ($matcher->getModules() as $module) {
			if ($module instanceof PhpModule) {
				$this->phpModule = $module;
				$this->phpModule->addDefaultGroup("json:" . JsonProperty::DEFAULT_GROUP);
				$present = true;
				break;
			}
		}

		if (!$present) {
			throw new MetaException(
				"JsonModule depends on PhpModule. Please add PhpModule first."
			);
		}
	}

	public function onBeforeGenerate(AbstractMetaSpec $spec, MetaSpecMatcher $matcher, Type $type)
	{
		$annotations = $type->getAnnotations();

		foreach ($type->getAnnotations(JsonDiscriminatorMap::class) as $jsonDiscriminatorMap) {
			/** @var JsonDiscriminatorMap $jsonDiscriminatorMap */

			$annotations[] = $phpDiscriminatorMap = new PhpDiscriminatorMap();
			$phpDiscriminatorMap->map = $jsonDiscriminatorMap->map;
			$phpDiscriminatorMap->group = "json:" . $jsonDiscriminatorMap->group;
		}

		foreach ($type->getAnnotations(JsonDiscriminatorProperty::class) as $jsonDiscriminatorProperty) {
			/** @var JsonDiscriminatorProperty $jsonDiscriminatorProperty */

			$annotations[] = $phpDiscriminatorOffset = new PhpDiscriminatorOffset();
			$phpDiscriminatorOffset->offset = $jsonDiscriminatorProperty->name;
			$phpDiscriminatorOffset->group = "json:" . $jsonDiscriminatorProperty->group;
		}

		$type->setAnnotations($annotations);

		foreach ($type->getProperties() as $property) {
			if ($property->hasAnnotation(Transient::class)) {
				continue;
			}

			$hasDefaultGroup = false;
			foreach ($property->getAnnotations(JsonProperty::class) as $annotation) {
				/** @var JsonProperty $annotation */

				if ($annotation->group === JsonProperty::DEFAULT_GROUP) {
					$hasDefaultGroup = true;
				}

				if ($annotation->name === null) {
					$annotation->name = $property->getName();
				}
			}

			$annotations = $property->getAnnotations();

			if (!$hasDefaultGroup) {
				$annotations[] = $jsonProperty = new JsonProperty();
				$jsonProperty->name = $property->getName();
			}

			$property->setAnnotations($annotations);

			foreach ($property->getAnnotations(JsonProperty::class) as $jsonProperty) {
				/** @var JsonProperty $jsonProperty */
				$annotations[] = $arrayOffset = new PhpArrayOffset();
				$arrayOffset->offset = $jsonProperty->name;
				$arrayOffset->group = "json:" . $jsonProperty->group;
				$arrayOffset->ignoreNull = $jsonProperty->ignoreNull;
			}

			$property->setAnnotations($annotations);
		}
	}

	public function onGenerate(AbstractMetaSpec $spec, MetaSpecMatcher $matcher, Type $type, ClassType $class)
	{
		$ns = $class->getNamespace();

		$inputOutputClasses = array($type->getName() => true);
		foreach ($type->getAnnotations(JsonDiscriminatorMap::class) as $discriminatorMap) {
			/** @var JsonDiscriminatorMap $discriminatorMap */
			foreach ($discriminatorMap->map as $value => $className) {
				$inputOutputClasses[$className] = true;
			}
		}
		$inputOutputClasses = array_keys($inputOutputClasses);
		sort($inputOutputClasses);
		$inputOutputTypeHint = array();
		foreach ($inputOutputClasses as $className) {
			$ns->addUse($className, null, $alias);
			$inputOutputTypeHint[] = $alias;
		}
		$inputOutputTypeHint = implode("|", $inputOutputTypeHint);

		$ns->addUse(JsonMetaInterface::class);
		$ns->addUse($type->getName(), null, $typeAlias);
		$class->addImplement(JsonMetaInterface::class);

		// fromJson()
		$fromJson = $class->addMethod("fromJson");
		$fromJson->setStatic(true);
		$fromJson->addParameter("json");
		$fromJson->addParameter("group")->setOptional(true);
		$fromJson->addParameter("object")->setOptional(true);
		$fromJson
			->addComment("Creates \\{$type->getName()} from JSON array / JSON serialized string")
			->addComment("")
			->addComment("@param array|string \$json")
			->addComment("@param string \$group")
			->addComment("@param {$inputOutputTypeHint} \$object")
			->addComment("")
			->addComment("@throws \\InvalidArgumentException")
			->addComment("")
			->addComment("@return {$inputOutputTypeHint}");

		$fromJson
			->addBody("if (is_array(\$json)) {")
			->addBody("\t// ok, nothing to do here")
			->addBody("} elseif (is_string(\$json)) {")
			->addBody("\t\$decoded = json_decode(\$json, true);")
			->addBody("\tif (\$decoded === null && \$json !== '' && strcasecmp(\$json, 'null')) {")
			->addBody("\t\tthrow new \\InvalidArgumentException('Could not decode given JSON: ' . \$json . '.');")
			->addBody("\t}")
			->addBody("\t\$json = \$decoded;")
			->addBody("} else {")
			->addBody("\tthrow new \\InvalidArgumentException('Expected array, or string, ' . gettype(\$json) . ' given.');")
			->addBody("}")
			->addBody("");

		$fromJson
			->addBody("return self::fromObject(\$json, 'json:' . \$group, \$object);");

		// toJson()
		$toJson = $class->addMethod("toJson");
		$toJson->setStatic(true);
		$toJson->addParameter("object");
		$toJson->addParameter("group")->setOptional(true);
		$toJson->addParameter("filterOrOptions")->setOptional(true);
		$toJson->addParameter("options", 0)->setOptional(true);
		$toJson
			->addComment("Serializes \\{$type->getName()} to JSON string")
			->addComment("")
			->addComment("@param {$inputOutputTypeHint} \$object")
			->addComment("@param string \$group")
			->addComment("@param array|int \$filterOrOptions")
			->addComment("@param int \$options")
			->addComment("")
			->addComment("@throws \\InvalidArgumentException")
			->addComment("")
			->addComment("@return string");

		$toJson
			->addBody("if (is_int(\$filterOrOptions)) {")
			->addBody("\t\$options = \$filterOrOptions;")
			->addBody("\t\$filterOrOptions = null;")
			->addBody("}")
			->addBody("")
			->addBody("return json_encode(self::toObject(\$object, 'json:' . \$group, \$filterOrOptions), \$options);");

		// toJsonString()
		$toJsonString = $class->addMethod("toJsonString");
		$toJsonString->setStatic(true);
		$toJsonString->addParameter("object");
		$toJsonString->addParameter("group")->setOptional(true);
		$toJsonString
			->addComment("Serializes \\{$type->getName()} to JSON string (only for BC, TO BE REMOVED)")
			->addComment("")
			->addComment("@param {$inputOutputTypeHint} \$object")
			->addComment("@param string \$group")
			->addComment("")
			->addComment("@throws \\InvalidArgumentException")
			->addComment("")
			->addComment("@deprecated")
			->addComment("")
			->addComment("@return string");

		$toJsonString->addBody("return self::toJson(\$object, \$group);");

		// toJsonStringPretty()
		$toJsonStringPretty = $class->addMethod("toJsonStringPretty");
		$toJsonStringPretty->setStatic(true);
		$toJsonStringPretty->addParameter("object");
		$toJsonStringPretty->addParameter("group")->setOptional(true);
		$toJsonStringPretty
			->addComment("Serializes \\{$type->getName()} to JSON pretty string (only for BC, TO BE REMOVED)")
			->addComment("")
			->addComment("@param {$inputOutputTypeHint} \$object")
			->addComment("@param string \$group")
			->addComment("")
			->addComment("@throws \\InvalidArgumentException")
			->addComment("")
			->addComment("@deprecated")
			->addComment("")
			->addComment("@return string");

		$toJsonStringPretty->addBody("return self::toJson(\$object, \$group, JSON_PRETTY_PRINT);");

		// fromArrayOfJson(), toArrayOfJson()
		$fromArrayOfJson = $class->addMethod("fromArrayOfJson");
		$fromArrayOfJson->setStatic(true);
		$fromArrayOfJson->addParameter("input");
		$fromArrayOfJson->addParameter("group")->setOptional(true);
		$fromArrayOfJson->addParameter("object")->setOptional(true);

		$fromArrayOfJson
			->addComment("Creates \\{$type->getName()} from array of JSON-serialized properties")
			->addComment("")
			->addComment("@param array \$input")
			->addComment("@param string \$group")
			->addComment("@param {$inputOutputTypeHint} \$object")
			->addComment("")
			->addComment("@return {$inputOutputTypeHint}");

		$fromArrayOfJson
			->addBody("\$group = 'json:' . \$group;")
			->addBody("if (!isset(self::\$groups[\$group])) {")
			->addBody("\tthrow new \\InvalidArgumentException('Group \\'' . \$group . '\\' not supported for ' . " . Helpers::dump($type->getName()) . " . '.');")
			->addBody("} else {")
			->addBody("\t\$id = self::\$groups[\$group];")
			->addBody("}")
			->addBody("");


		$toArrayOfJson = $class->addMethod("toArrayOfJson");
		$toArrayOfJson->setStatic(true);
		$toArrayOfJson->addParameter("object");
		$toArrayOfJson->addParameter("group")->setOptional(true);
		$toArrayOfJson->addParameter("filterOrOptions", 0)->setOptional(true);
		$toArrayOfJson->addParameter("options", 0)->setOptional(true);
		$toArrayOfJson
			->addComment("Transforms \\{$type->getName()} into array of JSON-serialized strings")
			->addComment("")
			->addComment("@param {$inputOutputTypeHint} \$object")
			->addComment("@param string \$group")
			->addComment("@param array|int \$filterOrOptions")
			->addComment("@param int \$options")
			->addComment("")
			->addComment("@throws \\InvalidArgumentException")
			->addComment("")
			->addComment("@return array");

		$toArrayOfJson
			->addBody("if (is_int(\$filterOrOptions)) {")
			->addBody("\t\$options = \$filterOrOptions;")
			->addBody("\t\$filter = null;")
			->addBody("} else {")
			->addBody("\t\$filter = \$filterOrOptions;")
			->addBody("}")
			->addBody("")
			->addBody("\$group = 'json:' . \$group;")
			->addBody("if (!isset(self::\$groups[\$group])) {")
			->addBody("\tthrow new \\InvalidArgumentException('Group \\'' . \$group . '\\' not supported for ' . " . Helpers::dump($type->getName()) . " . '.');")
			->addBody("} else {")
			->addBody("\t\$id = self::\$groups[\$group];")
			->addBody("}")
			->addBody("")
			->addBody("\$output = (array)self::toObject(\$object, \$group, \$filter);")
			->addBody("");

		$groups = $class->getProperty("groups")->value;

		foreach ($type->getProperties() as $property) {
			if ($property->getType() instanceof ScalarType) {
				continue; // skip scalar fields
			}

			foreach ($property->getAnnotations(JsonProperty::class) as $jsonProperty) {
				/** @var JsonProperty $jsonProperty */
				$arrayOffset = new PhpArrayOffset();
				$arrayOffset->offset = $jsonProperty->name;
				$arrayOffset->group = "json:" . $jsonProperty->group;

				if ($this->phpModule->getMatchingPropertySerializer($property, $arrayOffset) !== null) {
					continue; // skip custom-serialized fields
				}

				$groupId = $groups[$arrayOffset->group];

				$inputPath = Helpers::dump($arrayOffset->offset);

				$fromArrayOfJson
					->addBody("if ((\$id & {$groupId}) > 0 && isset(\$input[{$inputPath}]) && is_string(\$input[{$inputPath}])) {")
					->addBody("\t\$decoded = json_decode(\$input[{$inputPath}], true);")
					->addBody("\tif (\$decoded === null && \$input[{$inputPath}] !== '' && strcasecmp(\$input[{$inputPath}], 'null')) {")
					->addBody("\t\tthrow new \\InvalidArgumentException('Could not decode given JSON: ' . \$input[{$inputPath}] . '.');")
					->addBody("\t}")
					->addBody("\t\$input[{$inputPath}] = \$decoded;")
					->addBody("}")
					->addBody("");

				$toArrayOfJson
					->addBody("if ((\$id & {$groupId}) > 0 && isset(\$output[{$inputPath}]) && (\$filter === null || isset(\$filter[{$inputPath}]))) {")
					->addBody("\t\$output[{$inputPath}] = json_encode(\$output[{$inputPath}], \$options);")
					->addBody("}")
					->addBody("");
			}
		}

		$fromArrayOfJson
			->addBody("/** @var object \$input */")
			->addBody("return self::fromObject(\$input, \$group, \$object);");

		$toArrayOfJson
			->addBody("return \$output;");
	}

}
