<?php
namespace Skrz\Meta\Reflection;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\PhpParser;
use ReflectionClass;

class Type extends ObjectType
{
	/** @var string */
	public $name;

	/** @var boolean */
	private $internal;

	/** @var boolean */
	private $userDefined;

	/** @var boolean */
	private $instantiable;

	/** @var string */
	private $fileName;

	/** @var integer */
	private $startLine;

	/** @var integer */
	private $endLine;

	/** @var boolean */
	private $docComment;

	/** @var Method */
	private $constructor;

	/** @var boolean */
	private $constructorInitialized;

	/** @var Method[] */
	private $methods;

	/** @var boolean */
	private $methodsInitialized;

	/** @var Property[] */
	private $properties;

	/** @var boolean */
	private $propertiesInitialized;

	/** @var array */
	private $constants;

	/** @var Type[] */
	private $interfaces;

	/** @var boolean */
	private $interfacesInitialized;

	/** @var string[] */
	private $interfaceNames;

	/** @var boolean */
	private $interface;

	/** @var Type[] */
	private $traits;

	/** @var boolean */
	private $traitsInitialized;

	/** @var string[] */
	private $traitNames;

	/** @var boolean */
	private $trait;

	/** @var boolean */
	private $abstract;

	/** @var boolean */
	private $final;

	/** @var integer */
	private $modifiers;

	/** @var Type */
	private $parentClass;

	/** @var boolean */
	private $parentClassInitialized;

	/** @var array */
	private $defaultProperties;

	/** @var boolean */
	private $iterateable;

	/** @var boolean */
	private $extensionName;

	/** @var string */
	private $namespaceName;

	/** @var string */
	private $shortName;

	/** @var object[] */
	private $annotations = [];

	/** @var string[] array of lowercased alias => FQN use statements */
	public $useStatements = [];


	public function __construct(ReflectionClass $reflection)
	{
		$this->reflection = $reflection;
	}


	public static function fromReflection(ReflectionClass $reflection = null)
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

		$stackExpression = $reflection->getName();

		if (isset($stack[$stackExpression])) {
			return $stack[$stackExpression];
		}

		$stack[$stackExpression] = $instance = new Type($reflection);

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
		$instance->internal = $reflection->isInternal();
		$instance->userDefined = $reflection->isUserDefined();
		$instance->instantiable = $reflection->isInstantiable();
		$instance->fileName = $reflection->getFileName();
		$instance->startLine = $reflection->getStartLine();
		$instance->endLine = $reflection->getEndLine();
		$instance->docComment = $reflection->getDocComment();
		$instance->constants = $reflection->getConstants();
		$instance->interfaceNames = $reflection->getInterfaceNames();
		$instance->interface = $reflection->isInterface();
		$instance->traitNames = PHP_VERSION_ID >= 50400 ? $reflection->getTraitNames() : null;
		$instance->trait = PHP_VERSION_ID >= 50400 ? $reflection->isTrait() : null;
		$instance->abstract = $reflection->isAbstract();
		$instance->final = $reflection->isFinal();
		$instance->modifiers = $reflection->getModifiers();
		$instance->defaultProperties = $reflection->getDefaultProperties();
		$instance->iterateable = $reflection->isIterateable();
		$instance->extensionName = $reflection->getExtensionName();
		$instance->namespaceName = $reflection->getNamespaceName();
		$instance->shortName = $reflection->getShortName();
		$instance->annotations = $reader->getClassAnnotations($reflection);
		$instance->useStatements = $phpParser->parseClass($reflection);
		$instance->useStatements[strtolower($reflection->getShortName())] = $reflection->getName();


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
	public function isInstantiable()
	{
		return $this->instantiable;
	}


	/**
	 * @param boolean $instantiable
	 * @return $this
	 */
	public function setInstantiable($instantiable)
	{
		$this->instantiable = $instantiable;
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
	 * @return Method
	 */
	public function getConstructor()
	{
		if (!$this->constructorInitialized) {
			$this->constructor = Method::fromReflection($this->reflection->getConstructor() ? $this->reflection->getConstructor() : null);
			$this->constructorInitialized = true;
		}
		return $this->constructor;
	}


	/**
	 * @param Method $constructor
	 * @return $this
	 */
	public function setConstructor($constructor)
	{
		$this->constructor = $constructor;
		return $this;
	}


	/**
	 * @return Method[]
	 */
	public function getMethods()
	{
		if (!$this->methodsInitialized) {
			$this->methods = array();
			foreach ($this->reflection->getMethods() as $key => $value) {
				$this->methods[$key] = Method::fromReflection($value);
			}
			$this->methodsInitialized = true;
		}
		return $this->methods;
	}


	/**
	 * @param Method[] $methods
	 * @return $this
	 */
	public function setMethods($methods)
	{
		$this->methods = $methods;
		return $this;
	}


	/**
	 * @return Property[]
	 */
	public function getProperties()
	{
		if (!$this->propertiesInitialized) {
			$this->properties = array();
			foreach ($this->reflection->getProperties() as $key => $value) {
				$this->properties[$key] = Property::fromReflection($value);
			}
			$this->propertiesInitialized = true;
		}
		return $this->properties;
	}


	/**
	 * @param Property[] $properties
	 * @return $this
	 */
	public function setProperties($properties)
	{
		$this->properties = $properties;
		return $this;
	}


	/**
	 * @return array
	 */
	public function getConstants()
	{
		return $this->constants;
	}


	/**
	 * @param array $constants
	 * @return $this
	 */
	public function setConstants($constants)
	{
		$this->constants = $constants;
		return $this;
	}


	/**
	 * @return Type[]
	 */
	public function getInterfaces()
	{
		if (!$this->interfacesInitialized) {
			$this->interfaces = array();
			foreach ($this->reflection->getInterfaces() as $key => $value) {
				$this->interfaces[$key] = Type::fromReflection($value);
			}
			$this->interfacesInitialized = true;
		}
		return $this->interfaces;
	}


	/**
	 * @param Type[] $interfaces
	 * @return $this
	 */
	public function setInterfaces($interfaces)
	{
		$this->interfaces = $interfaces;
		return $this;
	}


	/**
	 * @return string[]
	 */
	public function getInterfaceNames()
	{
		return $this->interfaceNames;
	}


	/**
	 * @param string[] $interfaceNames
	 * @return $this
	 */
	public function setInterfaceNames($interfaceNames)
	{
		$this->interfaceNames = $interfaceNames;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function isInterface()
	{
		return $this->interface;
	}


	/**
	 * @param boolean $interface
	 * @return $this
	 */
	public function setInterface($interface)
	{
		$this->interface = $interface;
		return $this;
	}


	/**
	 * @return Type[]
	 */
	public function getTraits()
	{
		if (!$this->traitsInitialized) {
			$this->traits = array();
			if (PHP_VERSION_ID >= 50400) {
				foreach ($this->reflection->getTraits() as $key => $value) {
					$this->traits[$key] = Type::fromReflection($value);
				}
			}
			$this->traitsInitialized = true;
		}
		return $this->traits;
	}


	/**
	 * @param Type[] $traits
	 * @return $this
	 */
	public function setTraits($traits)
	{
		$this->traits = $traits;
		return $this;
	}


	/**
	 * @return string[]
	 */
	public function getTraitNames()
	{
		return $this->traitNames;
	}


	/**
	 * @param string[] $traitNames
	 * @return $this
	 */
	public function setTraitNames($traitNames)
	{
		$this->traitNames = $traitNames;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function isTrait()
	{
		return $this->trait;
	}


	/**
	 * @param boolean $trait
	 * @return $this
	 */
	public function setTrait($trait)
	{
		$this->trait = $trait;
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
	public function getParentClass()
	{
		if (!$this->parentClassInitialized) {
			$this->parentClass = Type::fromReflection($this->reflection->getParentClass() ? $this->reflection->getParentClass() : null);
			$this->parentClassInitialized = true;
		}
		return $this->parentClass;
	}


	/**
	 * @param Type $parentClass
	 * @return $this
	 */
	public function setParentClass($parentClass)
	{
		$this->parentClass = $parentClass;
		return $this;
	}


	/**
	 * @return array
	 */
	public function getDefaultProperties()
	{
		return $this->defaultProperties;
	}


	/**
	 * @param array $defaultProperties
	 * @return $this
	 */
	public function setDefaultProperties($defaultProperties)
	{
		$this->defaultProperties = $defaultProperties;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function isIterateable()
	{
		return $this->iterateable;
	}


	/**
	 * @param boolean $iterateable
	 * @return $this
	 */
	public function setIterateable($iterateable)
	{
		$this->iterateable = $iterateable;
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
	 * @return string[] array of lowercased alias => FQN use statements
	 */
	public function getUseStatements()
	{
		return $this->useStatements;
	}


	/**
	 * @param string[] $useStatements
	 * @return $this
	 */
	public function setUseStatements($useStatements)
	{
		$this->useStatements = $useStatements;
		return $this;
	}


	/**
	 * @param string $propertyName
	 * @return Property
	 */
	public function getProperty($propertyName)
	{
		foreach ($this->getProperties() as $property) {
			if ($property->getName() === $propertyName){
				return $property;
			}
		}
		return null;
	}


	/**
	 * @param string $methodName
	 * @return Method
	 */
	public function getMethod($methodName)
	{
		foreach ($this->getMethods() as $method) {
			if ($method->getName() === $methodName){
				return $method;
			}
		}
		return null;
	}


	public function isDateTime()
	{
		return is_a($this->getName(), \DateTimeInterface::class, true);
	}


	public function __toString()
	{
		return $this->getName();
	}
}
