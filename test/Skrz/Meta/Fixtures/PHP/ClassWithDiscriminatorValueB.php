<?php
namespace Skrz\Meta\Fixtures\PHP;

use Skrz\Meta\PHP\PhpArrayOffset;

class ClassWithDiscriminatorValueB extends ClassWithDiscriminatorMap
{

	/**
	 * @var int
	 *
	 * @PhpArrayOffset(group="top")
	 */
	public $b;

}
