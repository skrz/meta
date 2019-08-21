<?php
namespace Skrz\Meta;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Helpers;
use Skrz\Meta\Reflection\ArrayType;
use Skrz\Meta\Reflection\ScalarType;
use Skrz\Meta\Reflection\Type;

class BaseModule extends AbstractModule
{

	public function onAdd(AbstractMetaSpec $spec, MetaSpecMatcher $matcher)
	{
		// nothing to do
	}

	public function onBeforeGenerate(AbstractMetaSpec $spec, MetaSpecMatcher $matcher, Type $type)
	{
	}

	public function onGenerate(AbstractMetaSpec $spec, MetaSpecMatcher $matcher, Type $type, ClassType $class)
	{
		$namespace = $class->getNamespace();

		$namespace->addUse($type->getName(), null, $typeAlias);
		$class->setFinal(true);

		$class
			->addComment("Meta class for \\{$type->getName()}")
			->addComment("")
			->addComment("!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!")
			->addComment("!!!                                                     !!!")
			->addComment("!!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!")
			->addComment("!!!                                                     !!!")
			->addComment("!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");

		// constructor
		$constructor = $class->addMethod("__construct");
		$constructor
			->addComment("Constructor")
			->addBody("self::\$instance = \$this; // avoids cyclic dependency stack overflow");

		if ($type->getConstructor()) {
			if ($type->getConstructor()->isPublic()) {
				$constructor->setVisibility("public");
			} elseif ($type->getConstructor()->isProtected()) {
				$constructor->setVisibility("protected");
			} elseif ($type->getConstructor()->isPrivate()) {
				$constructor->setVisibility("private");
			}
		} else {
			$constructor->setVisibility("private");
		}

		// implement base interface
		$namespace->addUse(MetaInterface::class, null, $metaInterfaceAlias);
		$class->addImplement(MetaInterface::class);

		// getInstance() method
		$instance = $class->addProperty("instance");
		$instance->setStatic(true);
		$instance->setVisibility("private");
		$instance->addComment("@var {$class->getName()}");

		$getInstance = $class->addMethod("getInstance");
		$getInstance->setStatic(true);
		$getInstance
			->addComment("Returns instance of this meta class")
			->addComment("")
			->addComment("@return {$class->getName()}");

		$getInstance
			->addBody("if (self::\$instance === null) {")
			->addBody("\tnew self(); // self::\$instance assigned in __construct")
			->addBody("}")
			->addBody("return self::\$instance;");

		// create() method
		$create = $class->addMethod("create");
		$create->setStatic(true);
		$create
			->addComment("Creates new instance of \\{$type->getName()}")
			->addComment("")
			->addComment("@throws \\InvalidArgumentException")
			->addComment("")
			->addComment("@return {$typeAlias}");

		$create->addBody("switch (func_num_args()) {");

		$maxArguments = 8;
		$constructMethod = $type->getConstructor();
		for ($i = 0; $i <= $maxArguments; ++$i) {
			$create->addBody("\tcase {$i}:");

			if ($constructMethod && $i < $constructMethod->getNumberOfRequiredParameters()) {
				$create->addBody("\t\tthrow new \\InvalidArgumentException('At least {$constructMethod->getNumberOfRequiredParameters()} arguments have to be supplied.');");
			} else {
				$args = array();
				for ($j = 0; $j < $i; ++$j) {
					$args[] = "func_get_arg({$j})";
				}

				$create->addBody("\t\treturn new {$typeAlias}(" . implode(", ", $args) . ");");
			}
		}

		$create->addBody("\tdefault:");
		$create->addBody("\t\tthrow new \\InvalidArgumentException('More than {$maxArguments} arguments supplied, please be reasonable.');");
		$create->addBody("}");

		$class->getNamespace()->addUse(\Closure::class, null, $closureAlias);

		// reset() method
		$reset = $class->addMethod("reset");
		$reset->setStatic(true);
		$reset
			->addComment("Resets properties of \\{$type->getName()} to default values\n")
			->addComment("")
			->addComment("@param {$typeAlias} \$object")
			->addComment("")
			->addComment("@throws \\InvalidArgumentException")
			->addComment("")
			->addComment("@return void");

		$reset->addParameter("object");

		$resetProperty = $class->addProperty($reset->getName());
		$resetProperty->setStatic(true)
			->setVisibility("private")
			->addComment("@var callable");

		$reset
			->addBody("if (!(\$object instanceof {$typeAlias})) {")
			->addBody("\tthrow new \\InvalidArgumentException('You have to pass object of class {$type->getName()}.');")
			->addBody("}")
			->addBody("")
			->addBody("if (self::\${$resetProperty->getName()} === null) {")
			->addBody("\tself::\${$resetProperty->getName()} = {$closureAlias}::bind(static function (\$object) {");

		foreach ($type->getProperties() as $property) {
			$reset->addBody("\t\t\$object->{$property->getName()} = " . Helpers::dump($property->getDefaultValue()) . ";");
		}

		$reset
			->addBody("\t}, null, {$typeAlias}::class);")
			->addBody("}")
			->addBody("")
			->addBody("return (self::\${$resetProperty->getName()})(\$object);");

		// hash() method
		$hash = $class->addMethod("hash");
		$hash->setStatic(true);
		$hash
			->addComment("Computes hash of \\{$type->getName()}")
			->addComment("")
			->addComment("@param object \$object")
			->addComment("@param string|resource \$algoOrCtx")
			->addComment("@param bool \$raw")
			->addComment("")
			->addComment("@return string|void");
		$hash->addParameter("object");
		$hash->addParameter("algoOrCtx")->setDefaultValue("md5")->setOptional(true);
		$hash->addParameter("raw")->setDefaultValue(false)->setOptional(true);

		$hashProperty = $class->addProperty($hash->getName());
		$hashProperty->setStatic(true)
			->setVisibility("private")
			->addComment("@var callable");

		$hash
			->addBody("if (self::\${$hashProperty->getName()} === null) {")
			->addBody("\tself::\${$hashProperty->getName()} = {$closureAlias}::bind(static function (\$object, \$algoOrCtx, \$raw) {")
			->addBody("\t\tif (is_string(\$algoOrCtx)) {")
			->addBody("\t\t\t\$ctx = hash_init(\$algoOrCtx);")
			->addBody("\t\t} else {")
			->addBody("\t\t\t\$ctx = \$algoOrCtx;")
			->addBody("\t\t}")
			->addBody("");

		foreach ($type->getProperties() as $property) {
			if ($property->hasAnnotation(Transient::class)) {
				continue;
			}

			if ($property->hasAnnotation(Hash::class)) {
				continue;
			}

			$objectPath = "\$object->{$property->getName()}";

			$hash->addBody("\t\tif (isset({$objectPath})) {");
			$hash->addBody("\t\t\thash_update(\$ctx, " . Helpers::dump($property->getName()) .");");

			$baseType = $property->getType();
			$indent = "\t\t\t";
			$before = "";
			$after = "";
			for ($i = 0; $baseType instanceof ArrayType; ++$i) {
				$arrayType = $baseType;
				$baseType = $arrayType->getBaseType();

				$before .= "{$indent}foreach ({$objectPath} instanceof \\Traversable ? {$objectPath} : (array){$objectPath} as \$v{$i}) {\n";
				$after = "{$indent}}\n" . $after;
				$indent .= "\t";
				$objectPath = "\$v{$i}";
			}

			if (!empty($before)) {
				$hash->addBody(rtrim($before));
			}

			if ($baseType instanceof ScalarType) {
				$hash->addBody("{$indent}hash_update(\$ctx, (string){$objectPath});");

			} elseif ($baseType instanceof Type) {
				if ($baseType->isDateTime()) {
					$hash->addBody("{$indent}hash_update(\$ctx, {$objectPath} instanceof \\DateTimeInterface ? {$objectPath}->format(\\DateTime::ISO8601) : '');");
				} else {
					$propertyTypeMetaClassName = $spec->createMetaClassName($baseType);
					$namespace->addUse($propertyTypeMetaClassName, null, $propertyTypeMetaClassNameAlias);
					$hash->addBody("{$indent}{$propertyTypeMetaClassNameAlias}::hash({$objectPath}, \$ctx);");
				}

			} else {
				throw new MetaException("Unsupported property type " . get_class($baseType) . " ({$type->getName()}::\${$property->getName()}).");
			}

			if (!empty($after)) {
				$hash->addBody(rtrim($after));
			}

			$hash->addBody("\t\t}")->addBody("");
		}

		$hash
			->addBody("\t\tif (is_string(\$algoOrCtx)) {")
			->addBody("\t\t\treturn hash_final(\$ctx, \$raw);")
			->addBody("\t\t} else {")
			->addBody("\t\t\treturn null;")
			->addBody("\t\t}")
			->addBody("\t}, null, {$typeAlias}::class);")
			->addBody("}")
			->addBody("")
			->addBody("return (self::\${$hashProperty->getName()})(\$object, \$algoOrCtx, \$raw);");
	}

}
