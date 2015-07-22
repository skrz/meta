<?php
namespace Skrz\Meta\Fixtures\PHP;

class ClassWithProtectedProperty
{

	/**
	 * @var string
	 */
	protected $property;

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
