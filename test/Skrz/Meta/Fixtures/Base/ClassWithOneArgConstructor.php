<?php
namespace Skrz\Meta\Fixtures\Base;

class ClassWithOneArgConstructor
{

	/** @var string */
	public $variable;

	public function __construct($variable)
	{
		$this->variable = $variable;
	}

}
