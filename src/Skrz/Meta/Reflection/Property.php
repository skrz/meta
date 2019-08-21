<?php
namespace Skrz\Meta\Reflection;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\PhpParser;
use ReflectionProperty;

class Property
{
	/** @var string */
	public $name;

	/** @var string */
	public $class;

	/** @var boolean */
	private $public;

	/** @var boolean */
	private $private;

	/** @var boolean */
	private $protected;

	/** @var boolean */
	private $static;

	/** @var boolean */
	private $default;

	/** @var integer */
	private $modifiers;

	/** @var Type */
	private $declaringClass;

	/** @var boolean */
	private $docComment;

	/** @var object[] */
	private $annotations = [];

	/** @var mixed default value */
	public $defaultValue;

	/** @var MixedType */
	public $type;


	public function __construct(ReflectionProperty $reflection)
	{
		$this->reflection = $reflection;
	}


	public static function fromReflection(ReflectionProperty $reflection = null)
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

		$stackExpression = $reflection->getDeclaringClass()->getName() . '::'. $reflection->getName();

		if (isset($stack[$stackExpression])) {
			return $stack[$stackExpression];
		}

		$stack[$stackExpression] = $instance = new Property($reflection);

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
		$instance->public = $reflection->isPublic();
		$instance->private = $reflection->isPrivate();
		$instance->protected = $reflection->isProtected();
		$instance->static = $reflection->isStatic();
		$instance->default = $reflection->isDefault();
		$instance->modifiers = $reflection->getModifiers();
		$instance->docComment = $reflection->getDocComment();
		$instance->annotations = $reader->getPropertyAnnotations($reflection);
		$instance->declaringClass = Type::fromReflection($reflection->getDeclaringClass() ? $reflection->getDeclaringClass() : null, $stack, $reader, $phpParser);

		$defaultProperties = $reflection->getDeclaringClass()->getDefaultProperties();
		if (isset($defaultProperties[$instance->name])) {
			$instance->defaultValue = $defaultProperties[$instance->name];
		}
		if (preg_match('/@var\\s+([a-zA-Z0-9\\\\\\[\\]_]+)/', $instance->docComment, $m)) {
			$typeString = $m[1];
		}
		if (isset($typeString)) {
			$instance->type = MixedType::fromString($typeString, $stack, $reader, $phpParser, $instance->declaringClass);
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
	public function isPublic()
	{
		return $this->public;
	}


	/**
	 * @param boolean $public
	 * @return $this
	 */
	public function setPublic($public)
	{
		$this->public = $public;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function isPrivate()
	{
		return $this->private;
	}


	/**
	 * @param boolean $private
	 * @return $this
	 */
	public function setPrivate($private)
	{
		$this->private = $private;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function isProtected()
	{
		return $this->protected;
	}


	/**
	 * @param boolean $protected
	 * @return $this
	 */
	public function setProtected($protected)
	{
		$this->protected = $protected;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function isStatic()
	{
		return $this->static;
	}


	/**
	 * @param boolean $static
	 * @return $this
	 */
	public function setStatic($static)
	{
		$this->static = $static;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function isDefault()
	{
		return $this->default;
	}


	/**
	 * @param boolean $default
	 * @return $this
	 */
	public function setDefault($default)
	{
		$this->default = $default;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getModifiers()
	{
		return $this->modifiers;
	}


	/**
	 * @param integer $modifiers
	 * @return $this
	 */
	public function setModifiers($modifiers)
	{
		$this->modifiers = $modifiers;
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
	 * @return boolean
	 */
	public function getDocComment()
	{
		return $this->docComment;
	}


	/**
	 * @param boolean $docComment
	 * @return $this
	 */
	public function setDocComment($docComment)
	{
		$this->docComment = $docComment;
		return $this;
	}


	/**
	 * @param string $annotationClassName if supplied, returns only annotations of given class name
	 * @return object[]
	 */
	public function getAnnotations($annotationClassName = null)
	{
		if ($annotationClassName === null) {
			return $this->annotations;
		} else {

			$annotations = array();
			foreach ($this->annotations as $annotation) {
				if (is_a($annotation, $annotationClassName)) {
					$annotations[] = $annotation;
				}
			}
			return $annotations;
		}
	}


	/**
	 * @param string $annotationClassName
	 * @throws \InvalidArgumentException
	 * @return object
	 */
	public function getAnnotation($annotationClassName)
	{
		$annotations = $this->getAnnotations($annotationClassName);
		if (isset($annotations[1])) {
			throw new \InvalidArgumentException('More than one annotation of class ' . $annotationClassName . '.');
		} elseif (isset($annotations[0])) {
			return $annotations[0];
		} else {
			return null;
		}
	}


	/**
	 * @param string $annotationClassName
	 * @return boolean
	 */
	public function hasAnnotation($annotationClassName)
	{
		return count($this->getAnnotations($annotationClassName)) > 0;
	}


	/**
	 * @var $annotations object[]
	 * @return $this
	 */
	public function setAnnotations($annotations)
	{
		$this->annotations = $annotations;
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
