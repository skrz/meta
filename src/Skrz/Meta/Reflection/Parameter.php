<?php
namespace Skrz\Meta\Reflection;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\PhpParser;
use ReflectionParameter;

class Parameter
{
	/** @var string */
	public $name;

	/** @var boolean */
	private $passedByReference;

	/** @var Method */
	private $declaringFunction;

	/** @var Type */
	private $declaringClass;

	/** @var Type */
	private $class;

	/** @var boolean */
	private $classInitialized;

	/** @var boolean */
	private $array;

	/** @var boolean */
	private $callable;

	/** @var integer */
	private $position;

	/** @var boolean */
	private $optional;

	/** @var boolean */
	private $defaultValueAvailable;

	/** @var mixed */
	private $defaultValue;

	/** @var boolean */
	private $defaultValueConstant;

	/** @var MixedType */
	public $type;


	public function __construct(ReflectionParameter $reflection)
	{
		$this->reflection = $reflection;
	}


	public static function fromReflection(ReflectionParameter $reflection = null)
	{
		if (!defined('PHP_VERSION_ID')) {
			$v = explode('.', PHP_VERSION);
			define('PHP_VERSION_ID', ($v[0] * 10000 + $v[1] * 100 + $v[2]));
		}

		if ($reflection === null) {
			return null;
		}

		if (func_num_args() > 1) {
			$stack = func_get_arg(1);
		} else {
			$stack = new \ArrayObject();
		}

		$stackExpression = $reflection->getDeclaringClass()->getName() . '::' . $reflection->getDeclaringFunction()->getName() . '(' . $reflection->getPosition() . ')';

		if (isset($stack[$stackExpression])) {
			return $stack[$stackExpression];
		}

		$stack[$stackExpression] = $instance = new Parameter($reflection);

		if (func_num_args() > 2) {
			$reader = func_get_arg(2);
		} else {
			$reader = new AnnotationReader();
		}

		if (func_num_args() > 3) {
			$phpParser = func_get_arg(3);
		} else {
			$phpParser = new PhpParser();
		}

		$instance->name = $reflection->getName();
		$instance->passedByReference = $reflection->isPassedByReference();
		$instance->array = $reflection->isArray();
		$instance->callable = PHP_VERSION_ID >= 50400 ? $reflection->isCallable() : null;
		$instance->position = $reflection->getPosition();
		$instance->optional = $reflection->isOptional();
		$instance->defaultValueAvailable = $reflection->isDefaultValueAvailable();
		$instance->defaultValue = $reflection->isDefaultValueAvailable() ? $reflection->getDefaultValue() : null;
		$instance->defaultValueConstant = PHP_VERSION_ID >= 50500 && $reflection->isDefaultValueAvailable() ? $reflection->isDefaultValueConstant() : null;
		$instance->declaringFunction = Method::fromReflection($reflection->getDeclaringFunction() ? $reflection->getDeclaringFunction() : null, $stack, $reader, $phpParser);
		$instance->declaringClass = Type::fromReflection($reflection->getDeclaringClass() ? $reflection->getDeclaringClass() : null, $stack, $reader, $phpParser);

		if (preg_match('/@param\\s+([a-zA-Z0-9\\\\\\[\\]_]+)\\s+\\$' . preg_quote($instance->name) . '/', $instance->declaringFunction->getDocComment(), $m)) {
			$typeString = $m[1];
		}
		if (isset($typeString)) {
			$instance->type = MixedType::fromString($typeString, $stack, $reader, $phpParser, $instance->declaringClass);
		} elseif ($reflection->getClass()) {
			$instance->type = Type::fromReflection($reflection->getClass(), $stack, $reader, $phpParser, $instance->declaringClass);
		} elseif ($reflection->isArray()) {
			$instance->type = MixedType::fromString('array', $stack, $reader, $phpParser, $instance->declaringClass);
		} else {
			$instance->type = MixedType::getInstance();
		}

		return $instance;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function isPassedByReference()
	{
		return $this->passedByReference;
	}


	/**
	 * @param boolean $passedByReference
	 * @return $this
	 */
	public function setPassedByReference($passedByReference)
	{
		$this->passedByReference = $passedByReference;
		return $this;
	}


	/**
	 * @return Method
	 */
	public function getDeclaringFunction()
	{
		return $this->declaringFunction;
	}


	/**
	 * @param Method $declaringFunction
	 * @return $this
	 */
	public function setDeclaringFunction($declaringFunction)
	{
		$this->declaringFunction = $declaringFunction;
		return $this;
	}


	/**
	 * @return Type
	 */
	public function getDeclaringClass()
	{
		return $this->declaringClass;
	}


	/**
	 * @param Type $declaringClass
	 * @return $this
	 */
	public function setDeclaringClass($declaringClass)
	{
		$this->declaringClass = $declaringClass;
		return $this;
	}


	/**
	 * @return Type
	 */
	public function getClass()
	{
		if (!$this->classInitialized) {
			$this->class = Type::fromReflection($this->reflection->getClass() ? $this->reflection->getClass() : null);
			$this->classInitialized = true;
		}
		return $this->class;
	}


	/**
	 * @param Type $class
	 * @return $this
	 */
	public function setClass($class)
	{
		$this->class = $class;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function isArray()
	{
		return $this->array;
	}


	/**
	 * @param boolean $array
	 * @return $this
	 */
	public function setArray($array)
	{
		$this->array = $array;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function isCallable()
	{
		return $this->callable;
	}


	/**
	 * @param boolean $callable
	 * @return $this
	 */
	public function setCallable($callable)
	{
		$this->callable = $callable;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getPosition()
	{
		return $this->position;
	}


	/**
	 * @param integer $position
	 * @return $this
	 */
	public function setPosition($position)
	{
		$this->position = $position;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function isOptional()
	{
		return $this->optional;
	}


	/**
	 * @param boolean $optional
	 * @return $this
	 */
	public function setOptional($optional)
	{
		$this->optional = $optional;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function isDefaultValueAvailable()
	{
		return $this->defaultValueAvailable;
	}


	/**
	 * @param boolean $defaultValueAvailable
	 * @return $this
	 */
	public function setDefaultValueAvailable($defaultValueAvailable)
	{
		$this->defaultValueAvailable = $defaultValueAvailable;
		return $this;
	}


	/**
	 * @return mixed
	 */
	public function getDefaultValue()
	{
		return $this->defaultValue;
	}


	/**
	 * @param mixed $defaultValue
	 * @return $this
	 */
	public function setDefaultValue($defaultValue)
	{
		$this->defaultValue = $defaultValue;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function isDefaultValueConstant()
	{
		return $this->defaultValueConstant;
	}


	/**
	 * @param boolean $defaultValueConstant
	 * @return $this
	 */
	public function setDefaultValueConstant($defaultValueConstant)
	{
		$this->defaultValueConstant = $defaultValueConstant;
		return $this;
	}


	/**
	 * @return MixedType
	 */
	public function getType()
	{
		return $this->type;
	}


	/**
	 * @param MixedType $type
	 * @return $this
	 */
	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}
}
