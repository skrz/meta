<?php
namespace Skrz\Meta\Fixtures\PHP;

class ArrayCollection implements \IteratorAggregate
{

	/** @var array */
	private $array;

	public function __construct(array $array)
	{
		$this->array = $array;
	}

	public function getIterator()
	{
		return new \ArrayIterator($this->array);
	}

}
