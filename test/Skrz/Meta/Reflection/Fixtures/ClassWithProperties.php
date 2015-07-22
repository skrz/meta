<?php
namespace Skrz\Meta\Reflection\Fixtures;

class ClassWithProperties
{

	private $privateProperty;

	protected $protectedProperty;

	public $publicProperty;

	public $untypedProperty;

	/** @var mixed */
	public $mixedProperty;

	/**
	 * @var int
	 */
	public $intProperty;

	/** @var double */
	public $floatProperty;

	/** @var boolean */
	public $boolProperty;

	/** @var resource  this is a resource property */
	public $resourceProperty;

	/** @var callback      */
	public $callableProperty;

	/** @var     string */
	public $stringProperty;

	/** @var SimpleClass */
	public $classProperty;

}
