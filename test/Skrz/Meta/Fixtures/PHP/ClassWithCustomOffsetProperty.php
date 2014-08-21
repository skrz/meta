<?php
namespace Skrz\Meta\Fixtures\PHP;

use Skrz\Meta\PHP\PhpArrayOffset;

class ClassWithCustomOffsetProperty
{

	/**
	 * @var string
	 * @PhpArrayOffset("some-offset")
	 */
	public $property;

}
