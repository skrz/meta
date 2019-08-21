<?php
namespace Skrz\Meta\Fixtures\PHP;

class ClassWithPrivateProperty
{

	/**
	 * @var integer
	 */
	private $property;

	public function setProperty($property)
	{
		$this->property = $property;
		return $this;
	}

	public function getProperty()
	{
		return $this->property;
	}

}
