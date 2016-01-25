<?php
namespace Skrz\Meta\Fixtures\Base;

class ClassWithTwoArgConstructor
{

	/** @var int */
	public $variable1;

	/** @var float */
	public $variable2;

	public function __construct($variable1, $variable2)
	{
		$this->variable1 = $variable1;
		$this->variable2 = $variable2;
	}

}
