<?php
namespace Skrz\Meta\Fixtures\Base;

use Skrz\Meta\Hash;

class ClassToBeHashed
{

	/** @var string */
	public $a;

	/** @var int */
	public $b;

	/** @var float[][] */
	public $c;

	/** @var ClassToBeHashed */
	public $d;

	/** @var \DateTime */
	public $e;

	/**
	 * @var string
	 *
	 * @Hash
	 */
	public $h;

}
