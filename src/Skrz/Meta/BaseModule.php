<?php
namespace Skrz\Meta;

use Nette\PhpGenerator\ClassType;
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
			->addDocument("THIS CLASS IS AUTO-GENERATED! DO NOT EDIT!");

		// make constructor empty
		$constructor = $class->addMethod("__construct");
		$constructor
			->addDocument("Constructor made empty, so creating meta class has no side effects");

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
		$instance->addDocument("@var {$class->getName()}");

		$getInstance = $class->addMethod("getInstance");
		$getInstance->setStatic(true);
		$getInstance
			->addDocument("Returns instance of this meta class")
			->addDocument("")
			->addDocument("@return {$class->getName()}");

		$getInstance
			->addBody("if (self::\$instance === null) {")
			->addBody("\tself::\$instance = new self();")
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
			if ($property->hasAnnotation(Transient::class)) {
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
	}

}
