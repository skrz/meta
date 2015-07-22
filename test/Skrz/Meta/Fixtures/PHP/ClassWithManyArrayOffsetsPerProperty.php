<?php
namespace Skrz\Meta\Fixtures\PHP;

use Skrz\Meta\PHP\PhpArrayOffset;

class ClassWithManyArrayOffsetsPerProperty
{

	/**
	 * @var string
	 *
	 * @PhpArrayOffset("property")
	 * @PhpArrayOffset("foo", group="foo")
	 * @PhpArrayOffset("bar", group="bar")
	 */
	public $property;

}
