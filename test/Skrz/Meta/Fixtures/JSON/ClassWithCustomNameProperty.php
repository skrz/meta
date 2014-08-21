<?php
namespace Skrz\Meta\Fixtures\JSON;

use Skrz\Meta\JSON\JsonProperty;

class ClassWithCustomNameProperty
{

	/**
	 * @var string
	 *
	 * @JsonProperty("some_property")
	 */
	protected $someProperty;

	/**
	 * @param string $someProperty
	 * @return $this
	 */
	public function setSomeProperty($someProperty)
	{
		$this->someProperty = $someProperty;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSomeProperty()
	{
		return $this->someProperty;
	}

}
