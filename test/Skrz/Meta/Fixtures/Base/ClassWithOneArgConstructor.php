<?php
namespace Skrz\Meta\Fixtures\Base;

class ClassWithOneArgConstructor
{

	/** @var mixed */
	public $variable;

	public function __construct($variable)
	{
		$this->variable = $variable;
	}

}
