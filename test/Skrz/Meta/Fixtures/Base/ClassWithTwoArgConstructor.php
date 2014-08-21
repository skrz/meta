<?php
namespace Skrz\Meta\Fixtures\Base;

class ClassWithTwoArgConstructor 
{

	public $variable1;

	public $variable2;

	public function __construct($variable1, $variable2)
	{
		$this->variable1 = $variable1;
		$this->variable2 = $variable2;
	}


}
 