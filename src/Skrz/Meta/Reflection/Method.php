<?php
namespace Skrz\Meta\Reflection;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\PhpParser;
use ReflectionMethod;

class Method
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
	private $abstract;

	/** @var boolean */
	private $final;

	/** @var boolean */
	private $static;

	/** @var boolean */
	private $constructor;

	/** @var boolean */
	private $destructor;

	/** @var integer */
	private $modifiers;

	/** @var Type */
	private $declaringClass;

	/** @var Method */
	private $prototype;

	/** @var boolean */
	private $prototypeInitialized;

	/** @var boolean */
	private $closure;

	/** @var boolean */
	private $deprecated;

	/** @var boolean */
	private $internal;

	/** @var boolean */
	private $userDefined;

	/** @var boolean */
	private $generator;

	/** @var boolean */
	private $docComment;

	/** @var integer */
	private $endLine;

	/** @var boolean */
	private $extensionName;

	/** @var string */
	private $fileName;

	/** @var string */
	private $namespaceName;

	/** @var integer */
	private $numberOfParameters;

	/** @var integer */
	private $numberOfRequiredParameters;

	/** @var Parameter[] */
	private $parameters;

	/** @var boolean */
	private $parametersInitialized;

	/** @var string */
	private $shortName;

	/** @var integer */
	private $startLine;

	/** @var array */
	private $staticVariables;

	/** @var object[] */
	private $annotations = [];

	/** @var MixedType */
	public $type;


	public function __construct(ReflectionMethod $reflection)
	{
		$this->reflection = $reflection;
	}


	public static function fromReflection(ReflectionMethod $reflection = null)
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

		$stackExpression = $reflection->getDeclaringClass()->getName() . '::' . $reflection->getName();

		if (isset($stack[$stackExpression])) {
			return $stack[$stackExpression];
		}

		$stack[$stackExpression] = $instance = new Method($reflection);

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

		$instance->public = $reflection->isPublic();
		$instance->private = $reflection->isPrivate();
		$instance->protected = $reflection->isProtected();
		$instance->abstract = $reflection->isAbstract();
		$instance->final = $reflection->isFinal();
		$instance->static = $reflection->isStatic();
		$instance->constructor = $reflection->isConstructor();
		$instance->destructor = $reflection->isDestructor();
		$instance->modifiers = $reflection->getModifiers();
		$instance->closure = $reflection->isClosure();
		$instance->deprecated = $reflection->isDeprecated();
		$instance->internal = $reflection->isInternal();
		$instance->userDefined = $reflection->isUserDefined();
		$instance->generator = PHP_VERSION_ID >= 50500 ? $reflection->isGenerator() : null;
		$instance->docComment = $reflection->getDocComment();
		$instance->endLine = $reflection->getEndLine();
		$instance->extensionName = $reflection->getExtensionName();
		$instance->fileName = $reflection->getFileName();
		$instance->name = $reflection->getName();
		$instance->namespaceName = $reflection->getNamespaceName();
		$instance->numberOfParameters = $reflection->getNumberOfParameters();
		$instance->numberOfRequiredParameters = $reflection->getNumberOfRequiredParameters();
		$instance->shortName = $reflection->getShortName();
		$instance->startLine = $reflection->getStartLine();
		$instance->staticVariables = $reflection->getStaticVariables();
		$instance->annotations = $reader->getMethodAnnotations($reflection);
		$instance->declaringClass = Type::fromReflection($reflection->getDeclaringClass() ? $reflection->getDeclaringClass() : null, $stack, $reader, $phpParser);

		if (preg_match('/@return\\s+([a-zA-Z0-9\\\\\\[\\]_]+)/', $instance->docComment, $m)) {
			$typeString = $m[1];
		}
		if (isset($typeString)) {
			$instance->type = MixedType::fromString($typeString, $stack, $reader, $phpParser, $instance->declaringClass);
		} else {
			$instance->type = VoidType::getInstance();
		}

		return $instance;
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
	public function isAbstract()
	{
		return $this->abstract;
	}


	/**
	 * @param boolean $abstract
	 * @return $this
	 */
	public function setAbstract($abstract)
	{
		$this->abstract = $abstract;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function isFinal()
	{
		return $this->final;
	}


	/**
	 * @param boolean $final
	 * @return $this
	 */
	public function setFinal($final)
	{
		$this->final = $final;
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
	public function isConstructor()
	{
		return $this->constructor;
	}


	/**
	 * @param boolean $constructor
	 * @return $this
	 */
	public function setConstructor($constructor)
	{
		$this->constructor = $constructor;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function isDestructor()
	{
		return $this->destructor;
	}


	/**
	 * @param boolean $destructor
	 * @return $this
	 */
	public function setDestructor($destructor)
	{
		$this->destructor = $destructor;
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
	 * @return Method
	 */
	public function getPrototype()
	{
		if (!$this->prototypeInitialized) {
			try {
				$this->prototype = Method::fromReflection($this->reflection->getPrototype() ? $this->reflection->getPrototype() : null);
			} catch (\ReflectionException $e) {
				$this->prototype = null;
			}
			$this->prototypeInitialized = true;
		}
		return $this->prototype;
	}


	/**
	 * @param Method $prototype
	 * @return $this
	 */
	public function setPrototype($prototype)
	{
		$this->prototype = $prototype;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function isClosure()
	{
		return $this->closure;
	}


	/**
	 * @param boolean $closure
	 * @return $this
	 */
	public function setClosure($closure)
	{
		$this->closure = $closure;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function isDeprecated()
	{
		return $this->deprecated;
	}


	/**
	 * @param boolean $deprecated
	 * @return $this
	 */
	public function setDeprecated($deprecated)
	{
		$this->deprecated = $deprecated;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function isInternal()
	{
		return $this->internal;
	}


	/**
	 * @param boolean $internal
	 * @return $this
	 */
	public function setInternal($internal)
	{
		$this->internal = $internal;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function isUserDefined()
	{
		return $this->userDefined;
	}


	/**
	 * @param boolean $userDefined
	 * @return $this
	 */
	public function setUserDefined($userDefined)
	{
		$this->userDefined = $userDefined;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function isGenerator()
	{
		return $this->generator;
	}


	/**
	 * @param boolean $generator
	 * @return $this
	 */
	public function setGenerator($generator)
	{
		$this->generator = $generator;
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
	 * @return integer
	 */
	public function getEndLine()
	{
		return $this->endLine;
	}


	/**
	 * @param integer $endLine
	 * @return $this
	 */
	public function setEndLine($endLine)
	{
		$this->endLine = $endLine;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getExtensionName()
	{
		return $this->extensionName;
	}


	/**
	 * @param boolean $extensionName
	 * @return $this
	 */
	public function setExtensionName($extensionName)
	{
		$this->extensionName = $extensionName;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getFileName()
	{
		return $this->fileName;
	}


	/**
	 * @param string $fileName
	 * @return $this
	 */
	public function setFileName($fileName)
	{
		$this->fileName = $fileName;
		return $this;
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
	 * @return string
	 */
	public function getNamespaceName()
	{
		return $this->namespaceName;
	}


	/**
	 * @param string $namespaceName
	 * @return $this
	 */
	public function setNamespaceName($namespaceName)
	{
		$this->namespaceName = $namespaceName;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getNumberOfParameters()
	{
		return $this->numberOfParameters;
	}


	/**
	 * @param integer $numberOfParameters
	 * @return $this
	 */
	public function setNumberOfParameters($numberOfParameters)
	{
		$this->numberOfParameters = $numberOfParameters;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getNumberOfRequiredParameters()
	{
		return $this->numberOfRequiredParameters;
	}


	/**
	 * @param integer $numberOfRequiredParameters
	 * @return $this
	 */
	public function setNumberOfRequiredParameters($numberOfRequiredParameters)
	{
		$this->numberOfRequiredParameters = $numberOfRequiredParameters;
		return $this;
	}


	/**
	 * @return Parameter[]
	 */
	public function getParameters()
	{
		if (!$this->parametersInitialized) {
			$this->parameters = array();
			foreach ($this->reflection->getParameters() as $key => $value) {
				$this->parameters[$key] = Parameter::fromReflection($value);
			}
			$this->parametersInitialized = true;
		}
		return $this->parameters;
	}


	/**
	 * @param Parameter[] $parameters
	 * @return $this
	 */
	public function setParameters($parameters)
	{
		$this->parameters = $parameters;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getShortName()
	{
		return $this->shortName;
	}


	/**
	 * @param string $shortName
	 * @return $this
	 */
	public function setShortName($shortName)
	{
		$this->shortName = $shortName;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getStartLine()
	{
		return $this->startLine;
	}


	/**
	 * @param integer $startLine
	 * @return $this
	 */
	public function setStartLine($startLine)
	{
		$this->startLine = $startLine;
		return $this;
	}


	/**
	 * @return array
	 */
	public function getStaticVariables()
	{
		return $this->staticVariables;
	}


	/**
	 * @param array $staticVariables
	 * @return $this
	 */
	public function setStaticVariables($staticVariables)
	{
		$this->staticVariables = $staticVariables;
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
	 * @param string|int $parameterName
	 * @return Parameter
	 */
	public function getParameter($parameterName)
	{
		foreach ($this->getParameters() as $parameter) {
			if ((is_string($parameterName) && $parameter->getName() === $parameterName) || (is_int($parameterName) && $parameter->getPosition() === $parameterName)){
				return $parameter;
			}
		}
		return null;
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
