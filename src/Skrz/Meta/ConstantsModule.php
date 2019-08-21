<?php
namespace Skrz\Meta;

use Nette\PhpGenerator\ClassType;
use Skrz\Meta\Reflection\Type;

class ConstantsModule extends AbstractModule
{

	private static $reserved = [
		"__HALT_COMPILER" => true,
		"ABSTRACT" => true,
		"AND" => true,
		"ARRAY" => true,
		"AS" => true,
		"BREAK" => true,
		"CALLABLE" => true,
		"CASE" => true,
		"CATCH" => true,
		"CLASS" => true,
		"CLONE" => true,
		"CONST" => true,
		"CONTINUE" => true,
		"DECLARE" => true,
		"DEFAULT" => true,
		"DIE" => true,
		"DO" => true,
		"ECHO" => true,
		"ELSE" => true,
		"ELSEIF" => true,
		"EMPTY" => true,
		"ENDDECLARE" => true,
		"ENDFOR" => true,
		"ENDFOREACH" => true,
		"ENDIF" => true,
		"ENDSWITCH" => true,
		"ENDWHILE" => true,
		"EVAL" => true,
		"EXIT" => true,
		"EXTENDS" => true,
		"FINAL" => true,
		"FINALLY" => true,
		"FOR" => true,
		"FOREACH" => true,
		"FUNCTION" => true,
		"GLOBAL" => true,
		"GOTO" => true,
		"IF" => true,
		"IMPLEMENTS" => true,
		"INCLUDE" => true,
		"INCLUDE_ONCE" => true,
		"INSTANCEOF" => true,
		"INSTEADOF" => true,
		"INTERFACE" => true,
		"ISSET" => true,
		"LIST" => true,
		"NAMESPACE" => true,
		"NEW" => true,
		"OR" => true,
		"PRINT" => true,
		"PRIVATE" => true,
		"PROTECTED" => true,
		"PUBLIC" => true,
		"REQUIRE" => true,
		"REQUIRE_ONCE" => true,
		"RETURN" => true,
		"STATIC" => true,
		"SWITCH" => true,
		"THROW" => true,
		"TRAIT" => true,
		"TRY" => true,
		"UNSET" => true,
		"USE" => true,
		"VAR" => true,
		"WHILE" => true,
		"XOR" => true,
		"YIELD" => true,
	];

	public function onAdd(AbstractMetaSpec $spec, MetaSpecMatcher $matcher)
	{
		// nothing to do
	}

	public function onBeforeGenerate(AbstractMetaSpec $spec, MetaSpecMatcher $matcher, Type $type)
	{
		// nothing to do
	}

	public function onGenerate(AbstractMetaSpec $spec, MetaSpecMatcher $matcher, Type $type, ClassType $class)
	{
		$class->addConstant("CLASS_NAME", $type->getName());
		$class->addConstant("SHORT_NAME", $type->getShortName());
		$class->addConstant("ENTITY_NAME", lcfirst($type->getShortName()));

		foreach ($type->getProperties() as $property) {
			$const = strtoupper(trim(preg_replace("/([A-Z])/", "_\$1", $property->getName()), "_"));

			if (isset(self::$reserved[$const])) {
				throw new MetaException("Property name constant for {$type->getName()}::\${$property->getName()} would result in reserved word.");
			}

			$class->addConstant($const, $property->getName());
		}
	}

}
