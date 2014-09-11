<?php
namespace Skrz\Meta\Fixtures\JSON;

use Skrz\Meta\JSON\JsonProperty;

class ClassWithDiscriminatorValueB extends ClassWithDiscriminatorMap
{

	/**
	 * @var int
	 *
	 * @JsonProperty(group="top")
	 */
	public $b;

}
