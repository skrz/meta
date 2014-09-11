<?php
namespace Skrz\Meta\Fixtures\JSON;

use Skrz\Meta\JSON\JsonProperty;

class ClassWithDiscriminatorValueA extends ClassWithDiscriminatorMap
{

	/**
	 * @var int
	 *
	 * @JsonProperty(group="top")
	 */
	public $a;

}
