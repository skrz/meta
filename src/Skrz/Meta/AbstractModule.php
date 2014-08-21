<?php
namespace Skrz\Meta;

use Skrz\Meta\Reflection\Type;
use Nette\PhpGenerator\ClassType;

abstract class AbstractModule
{

	/**
	 * @param AbstractMetaSpec $spec
	 * @param MetaSpecMatcher $matcher
	 * @return void
	 */
	abstract public function onAdd(AbstractMetaSpec $spec, MetaSpecMatcher $matcher);

	/**
	 * @param AbstractMetaSpec $spec
	 * @param MetaSpecMatcher $matcher
	 * @param Type $type
	 * @return void
	 */
	abstract public function onBeforeGenerate(AbstractMetaSpec $spec, MetaSpecMatcher $matcher, Type $type);

	/**
	 * @param AbstractMetaSpec $spec
	 * @param MetaSpecMatcher $matcher
	 * @param Type $type
	 * @param ClassType $class
	 * @return void
	 */
	abstract public function onGenerate(AbstractMetaSpec $spec, MetaSpecMatcher $matcher, Type $type, ClassType $class);

}
