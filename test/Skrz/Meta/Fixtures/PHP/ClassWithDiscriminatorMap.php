<?php
namespace Skrz\Meta\Fixtures\PHP;

use Skrz\Meta\PHP\PhpDiscriminatorMap;
use Skrz\Meta\PHP\PhpDiscriminatorOffset;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 *
 * @PhpDiscriminatorOffset("value")
 * @PhpDiscriminatorMap({
 *     "a" = "\Skrz\Meta\Fixtures\PHP\ClassWithDiscriminatorValueA",
 *     "b" = "\Skrz\Meta\Fixtures\PHP\ClassWithDiscriminatorValueB",
 * })
 *
 * @PhpDiscriminatorMap(map={
 *     "a" = "\Skrz\Meta\Fixtures\PHP\ClassWithDiscriminatorValueA",
 *     "b" = "\Skrz\Meta\Fixtures\PHP\ClassWithDiscriminatorValueB",
 * }, group="top")
 */
class ClassWithDiscriminatorMap
{

	/** @var string */
	public $value;

}
