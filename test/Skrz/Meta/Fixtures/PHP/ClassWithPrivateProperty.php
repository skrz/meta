<?php
namespace Skrz\Meta\Fixtures\PHP;

use Skrz\Meta\Transient;

class ClassWithPrivateProperty
{

	/**
	 * @var integer
	 * @Transient()
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
