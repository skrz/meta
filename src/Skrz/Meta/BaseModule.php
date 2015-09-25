<?php
namespace Skrz\Meta;

use Nette\PhpGenerator\ClassType;
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
		if ($type->isFinal()) {
			throw new MetaException("Cannot create meta for final class '{$type->getName()}'.");
		}
	}

	public function onGenerate(AbstractMetaSpec $spec, MetaSpecMatcher $matcher, Type $type, ClassType $class)
	{
		$namespace = $class->getNamespace();

		// extend base class
		$namespace->addUse($type->getName(), null, $typeAlias);
		$class->addExtend($type->getName());

		$class
			->addDocument("Meta class for \\{$type->getName()}")
			->addDocument("")
			->addDocument("!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!")
			->addDocument("!!!                                                     !!!")
			->addDocument("!!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!")
			->addDocument("!!!                                                     !!!")
			->addDocument("!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");

		// constructor
		$constructor = $class->addMethod("__construct");
		$constructor
			->addDocument("Constructor")
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
		$namespace->addUse("Skrz\\Meta\\MetaInterface", null, $metaInterfaceAlias);
		$class->addImplement("Skrz\\Meta\\MetaInterface");

		// getInstance() method
		$instance = $class->addProperty("instance");
		$instance->setStatic(true);
		$instance->setVisibility("private");
		$instance->addDocument("@var {$class->getName()}");

		$getInstance = $class->addMethod("getInstance");
		$getInstance->setStatic(true);
		$getInstance
			->addDocument("Returns instance of this meta class")
			->addDocument("")
			->addDocument("@return {$class->getName()}");

		$getInstance
			->addBody("if (self::\$instance === null) {")
			->addBody("\tnew self(); // self::\$instance assigned in __construct")
			->addBody("}")
			->addBody("return self::\$instance;");

		// create() method
		$create = $class->addMethod("create");
		$create->setStatic(true);
		$create
			->addDocument("Creates new instance of \\{$type->getName()}")
			->addDocument("")
			->addDocument("@throws \\InvalidArgumentException")
			->addDocument("")
			->addDocument("@return {$typeAlias}");

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

		// reset() method
		$reset = $class->addMethod("reset");
		$reset->setStatic(true);
		$reset
			->addDocument("Resets properties of \\{$type->getName()} to default values\n")
			->addDocument("")
			->addDocument("@param {$typeAlias} \$object")
			->addDocument("")
			->addDocument("@throws \\InvalidArgumentException")
			->addDocument("")
			->addDocument("@return void");

		$reset->addParameter("object");

		$reset
			->addBody("if (!(\$object instanceof {$typeAlias})) {")
			->addBody("\tthrow new \\InvalidArgumentException('You have to pass object of class {$type->getName()}.');")
			->addBody("}");

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
			$reset->addBody("\$object->{$property->getName()} = " . var_export($property->getDefaultValue(), true) . ";");
		}

		// hash() method
		$hash = $class->addMethod("hash");
		$hash->setStatic(true);
		$hash
			->addDocument("Computes hash of \\{$type->getName()}")
			->addDocument("")
			->addDocument("@param object \$object")
			->addDocument("@param string|resource \$algoOrCtx")
			->addDocument("@param bool \$raw")
			->addDocument("")
			->addDocument("@return string|void");
		$hash->addParameter("object");
		$hash->addParameter("algoOrCtx")->setDefaultValue("md5")->setOptional(true);
		$hash->addParameter("raw")->setDefaultValue(false)->setOptional(true);

		$hash
			->addBody("if (is_string(\$algoOrCtx)) {")
			->addBody("\t\$ctx = hash_init(\$algoOrCtx);")
			->addBody("} else {")
			->addBody("\t\$ctx = \$algoOrCtx;")
			->addBody("}")
			->addBody("");

		foreach ($type->getProperties() as $property) {
			if ($property->hasAnnotation("Skrz\\Meta\\Transient")) {
				continue;
			}

			if ($property->hasAnnotation("Skrz\\Meta\\Hash")) {
				continue;
			}

			$objectPath = "\$object->{$property->getName()}";

			$hash->addBody("if (isset({$objectPath})) {");
			$hash->addBody("\thash_update(\$ctx, " . var_export($property->getName(), true) .");");

			$baseType = $property->getType();
			$indent = "\t";
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
				$datetimeType = false;

				for ($t = $baseType; $t; $t = $t->getParentClass()) {
					if ($t->getName() === "DateTime") {
						$datetimeType = true;
						break;
					}
				}

				if ($datetimeType) {
					$hash->addBody("{$indent}hash_update(\$ctx, {$objectPath} instanceof \\DateTime ? {$objectPath}->format(\\DateTime::ISO8601) : '');");
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

			$hash->addBody("}")->addBody("");
		}

		$hash
			->addBody("if (is_string(\$algoOrCtx)) {")
			->addBody("\treturn hash_final(\$ctx, \$raw);")
			->addBody("} else {")
			->addBody("\treturn NULL;")
			->addBody("}");
	}

}
