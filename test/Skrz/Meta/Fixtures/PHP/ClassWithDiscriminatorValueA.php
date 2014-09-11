<?php
namespace Skrz\Meta\Fixtures\PHP;

use Skrz\Meta\PHP\PhpArrayOffset;

class ClassWithDiscriminatorValueA extends ClassWithDiscriminatorMap
{

	/**
	 * @var int
	 *
	 * @PhpArrayOffset(group="top")
	 */
	public $a;

}
