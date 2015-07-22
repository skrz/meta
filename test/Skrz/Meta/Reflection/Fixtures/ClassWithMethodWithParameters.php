<?php
namespace Skrz\Meta\Reflection\Fixtures;

class ClassWithMethodWithParameters
{

	public function mixedMethod($mixed, $anotherMixed)
	{

	}

	/**
	 * @param integer $int
	 * @param string $string
	 * @param array $array
	 * @param SimpleInterface $interface
	 * @param mixed $mixed
	 * @param array[array] $arrayArray
	 */
	public function method($int, $string, array $array, $interface, $mixed, $arrayArray)
	{

	}

}
