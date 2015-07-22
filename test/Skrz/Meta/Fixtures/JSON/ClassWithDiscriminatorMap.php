<?php
namespace Skrz\Meta\Fixtures\JSON;

use Skrz\Meta\JSON\JsonDiscriminatorMap;
use Skrz\Meta\JSON\JsonDiscriminatorProperty;
use Skrz\Meta\JSON\JsonProperty;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 *
 * @JsonDiscriminatorProperty("value")
 * @JsonDiscriminatorMap({
 *     "a" = "\Skrz\Meta\Fixtures\JSON\ClassWithDiscriminatorValueA",
 *     "b" = "\Skrz\Meta\Fixtures\JSON\ClassWithDiscriminatorValueB"
 * })
 *
 * @JsonDiscriminatorMap(map={
 *     "a" = "\Skrz\Meta\Fixtures\JSON\ClassWithDiscriminatorValueA",
 *     "b" = "\Skrz\Meta\Fixtures\JSON\ClassWithDiscriminatorValueB"
 * }, group="top")
 */
class ClassWithDiscriminatorMap
{

	/**
	 * @var string
	 *
	 * @JsonProperty(group="top")
	 */
	public $value;

}
