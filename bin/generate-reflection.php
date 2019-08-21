#!/usr/bin/env php
<?php
namespace Skrz\Meta;

class AParent
{

	public function aMethod($aParameter = null, AnInterface $anotherParameter = null)
	{

	}

}

interface AnInterface
{
}

interface AnotherInterface
{
}

trait ATrait
{

	public function smallTalk()
	{

	}

	public function bigTalk()
	{

	}

}

trait BTrait
{

	public function smallTalk()
	{

	}

	public function bigTalk()
	{

	}

}

class AClass extends AParent implements AnInterface, AnotherInterface
{

	use ATrait, BTrait {
		ATrait::smallTalk insteadof BTrait;
		BTrait::bigTalk insteadof ATrait;
	}

	const A_CONSTANT = 1;

	const ANOTHER_CONSTANT = "bar";

	public static $aStaticProperty = 1;

	public static $anotherStaticProperty = "bar";

	private $aProperty = null;

	public function __construct()
	{

	}

	public function aMethod($aParameter = AClass::A_CONSTANT, AnInterface $anotherParameter = null)
	{
		static $aStaticVariable = 1;
		static $anotherStaticVariable = "bar";
	}

}

require __DIR__ . "/../vendor/autoload.php";

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\PhpParser;
use Nette\PhpGenerator\PhpFile;
use Skrz\Meta\Reflection\Method;
use Skrz\Meta\Reflection\MixedType;
use Skrz\Meta\Reflection\ObjectType;
use Skrz\Meta\Reflection\Parameter;
use Skrz\Meta\Reflection\Property;
use Skrz\Meta\Reflection\Type;
use Skrz\Meta\Reflection\VoidType;

$classes = array(
	"ReflectionClass" => "Skrz\\Meta\\Reflection\\Type",
	"ReflectionMethod" => "Skrz\\Meta\\Reflection\\Method",
	"ReflectionProperty" => "Skrz\\Meta\\Reflection\\Property",
	"ReflectionParameter" => "Skrz\\Meta\\Reflection\\Parameter"
);

$someInstance = array(
	"ReflectionClass" => new \ReflectionClass("Skrz\\Meta\\AClass"),
	"ReflectionMethod" => $rm = new \ReflectionMethod("Skrz\\Meta\\AClass", "aMethod"),
	"ReflectionProperty" => new \ReflectionProperty("Skrz\\Meta\\AClass", "aProperty"),
	"ReflectionParameter" => $rm->getParameters()[1]
);

$annotationReaderMethod = array(
	"ReflectionClass" => "getClassAnnotations",
	"ReflectionMethod" => "getMethodAnnotations",
	"ReflectionProperty" => "getPropertyAnnotations",
	"ReflectionParameter" => null
);

$stackExpression = array(
	"ReflectionClass" => "\$reflection->getName()",
	"ReflectionMethod" => "\$reflection->getDeclaringClass()->getName() . '::' . \$reflection->getName()",
	"ReflectionProperty" => "\$reflection->getDeclaringClass()->getName() . '::'. \$reflection->getName()",
	"ReflectionParameter" => "\$reflection->getDeclaringClass()->getName() . '::' . \$reflection->getDeclaringFunction()->getName() . '(' . \$reflection->getPosition() . ')'"
);

$outputDirectory = __DIR__ . "/../src";

foreach ($classes as $className => $discoveryClassName) {
	$file = new PhpFile();
	$class = $file->addClass($discoveryClassName);
	$ns = $class->getNamespace();

	if ($className === "ReflectionClass") {
		$ns->addUse("Skrz\\Meta\\Reflection\\ObjectType", null, $mixedTypeAlias);
		$class->addExtend("Skrz\\Meta\\Reflection\\ObjectType");
	}

	$rc = new \ReflectionClass($className);

	$someInstanceOfClassName = $someInstance[$className];
	$currentAnnotationReaderMethod = $annotationReaderMethod[$className];

	foreach ($rc->getProperties() as $property) {
		$class->addProperty($property->getName())
			->addComment("@var string");
	}

	$constructor = $class->addMethod("__construct");
	$constructor->addParameter("reflection")->setTypeHint("\\" . $className);
	$constructor->addBody("\$this->reflection = \$reflection;");

	$fromReflection = $class->addMethod("fromReflection");
	$fromReflection
		->setStatic(true);

	$ns->addUse($className, null, $alias);
	$fromReflection->addParameter("reflection")
		->setTypeHint($alias)
		->setDefaultValue(null)
		->setOptional(true);

	$fromReflection
		->addBody("if (!defined('PHP_VERSION_ID')) {")
		->addBody("\t\$v = explode('.', PHP_VERSION);")
		->addBody("\tdefine('PHP_VERSION_ID', (\$v[0] * 10000 + \$v[1] * 100 + \$v[2]));")
		->addBody("}\n")
		->addBody("if (\$reflection === null) {\n\treturn null;\n}\n")
		->addBody("if (func_num_args() > 1) {\n\t\$stack = func_get_arg(1);\n} else {\n\t\$stack = new \\ArrayObject();\n}\n")
		->addBody("\$stackExpression = {$stackExpression[$className]};\n")
		->addBody("if (isset(\$stack[\$stackExpression])) {\n\treturn \$stack[\$stackExpression];\n}\n")
		->addBody("\$stack[\$stackExpression] = \$instance = new {$class->getName()}(\$reflection);\n");

	$ns->addUse("Doctrine\\Common\\Annotations\\AnnotationReader", null, $annotationReaderAlias);
	$fromReflection->addBody("if (func_num_args() > 2) {\n\t\$reader = func_get_arg(2);\n} else {\n\t\$reader = new {$annotationReaderAlias}();\n}\n");

	$ns->addUse("Doctrine\\Common\\Annotations\\PhpParser", null, $phpParserAlias);
	$fromReflection->addBody("if (func_num_args() > 3) {\n\t\$phpParser = func_get_arg(3);\n} else {\n\t\$phpParser = new {$phpParserAlias}();\n}\n");

	$endOfFromReflection = new \Nette\PhpGenerator\Method("fromReflection");

	foreach ($rc->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
		$is = false;

		if ($method->getName() !== "getTraitAliases" &&
			$method->getName() !== "getStaticProperties" &&
			$method->getName() !== "isCloneable" &&
			$method->getName() !== "isVariadic" &&
			$method->getName() !== "getType" && // TODO: PHP 7
			$method->getName() !== "isAnonymous" && // TODO: PHP 7
			$method->getName() !== "getReflectionConstants" &&
			$method->getName() !== "isIterable" &&
//			$method->getName() !== "getDefaultProperties" &&
			(strncmp($method->getName(), "get", 3) === 0 || ($is = (strncmp($method->getName(), "is", 2) === 0))) &&
			($method->getNumberOfParameters() === 0 || $method->getName() === "getProperties" || $method->getName() === "getMethods")
		) {
			$returnValue = $someInstanceOfClassName->{$method->getName()}();
			$returnType = gettype($returnValue);

			$arrayType = "";
			if ($returnType === "array") {
				if (gettype(current($returnValue)) === gettype(next($returnValue))) {
					$returnValue = current($returnValue);
					$returnType = gettype($returnValue);
					$arrayType = "[]";
				}
			}

			$objectType = false;
			if ($returnType === "object") {
				if (isset($classes[get_class($returnValue)])) {
					$ns->addUse($classes[get_class($returnValue)], null, $alias);
					$returnType = $alias;
					$objectType = true;
				} else {
					throw new \Exception("Could not process {$className}::{$method->getName()}().");
				}
			}

			if ($method->getName() === "getDefaultValue") {
				$returnType = "mixed";
			}

			if ($returnType === "NULL") {
				continue;
			}

			if ($is) {
				$propertyName = lcfirst(substr($method->getName(), 2));
			} else {
				$propertyName = lcfirst(substr($method->getName(), 3));
			}

			$propertyInitializedName = $propertyName . "Initialized";

			$properties = $class->getProperties();
			if (isset($properties[$propertyName])) {
				$property = $properties[$propertyName];
			} else {
				$property = $class->addProperty($propertyName)
					->setVisibility("private");
			}

			if ($objectType && !in_array($propertyName, ["declaringClass", "declaringFunction"])) {
				if (isset($properties[$propertyInitializedName])) {
					$propertyInitialized = $properties[$propertyInitializedName];
				} else {
					$propertyInitialized = $class->addProperty($propertyInitializedName)
						->setVisibility("private")
						->setComment("@var boolean");
				}
			} else {
				$propertyInitialized = null;
			}

			$property
				->setComment("@var {$returnType}{$arrayType}");

			$getter = $class->addMethod(($is ? "is" : "get") . ucfirst($propertyName));
			$getter
				->addComment("@return {$returnType}{$arrayType}");

			if ($propertyInitialized) {
				$getter
					->addBody("if (!\$this->{$propertyInitializedName}) {");

				if ($arrayType) {
					$getter->addBody("\t\$this->{$propertyName} = array();");

					$indent = "";
					if ($className === "ReflectionClass" && in_array($propertyName, array("traits"))) {
						$getter->addBody("\tif (PHP_VERSION_ID >= 50400) {");
						$indent = "\t";
					}

					$getter->addBody(
						"\t{$indent}foreach (\$this->reflection->{$method->getName()}() as \$key => \$value) {\n" .
						"\t{$indent}\t\$this->{$propertyName}[\$key] = {$returnType}::fromReflection(\$value);\n" .
						"\t{$indent}}"
					);

					if ($className === "ReflectionClass" && in_array($propertyName, array("traits"))) {
						$getter->addBody("\t}");
					}

				} elseif ($className === "ReflectionMethod" && $propertyName === "prototype") {
					$getter->addBody("\ttry {");
					$getter->addBody("\t\t\$this->{$propertyName} = {$returnType}::fromReflection(\$this->reflection->{$method->getName()}() ? \$this->reflection->{$method->getName()}() : null);");
					$getter->addBody("\t} catch (\\ReflectionException \$e) {");
					$getter->addBody("\t\t\$this->{$propertyName} = null;");
					$getter->addBody("\t}");

				} else {
					$getter->addBody("\t\$this->{$propertyName} = {$returnType}::fromReflection(\$this->reflection->{$method->getName()}() ? \$this->reflection->{$method->getName()}() : null);");
				}

				$getter
					->addBody("\t\$this->{$propertyInitializedName} = true;")
					->addBody("}");
			}

			$getter
				->addBody("return \$this->{$propertyName};");

			$setter = $class->addMethod("set" . ucfirst($propertyName));

			$setter
				->addComment("@param {$returnType}{$arrayType} \${$propertyName}")
				->addComment("@return \$this");

			$setter
				->addParameter($propertyName);

			$setter
				->addBody("\$this->{$propertyName} = \${$propertyName};")
				->addBody("return \$this;");

			if (in_array($propertyName, ["declaringClass", "declaringFunction"])) {
				$endOfFromReflection->addBody("\$instance->{$propertyName} = {$returnType}::fromReflection(\$reflection->{$method->getName()}() ? \$reflection->{$method->getName()}() : null, \$stack, \$reader, \$phpParser);");

			} elseif ($className === "ReflectionParameter" && in_array($propertyName, array("defaultValue"))) {
				$fromReflection->addBody("\$instance->{$propertyName} = \$reflection->isDefaultValueAvailable() ? \$reflection->{$method->getName()}() : null;");

			} elseif ($className === "ReflectionParameter" && in_array($propertyName, array("callable"))) {
				$fromReflection->addBody("\$instance->{$propertyName} = PHP_VERSION_ID >= 50400 ? \$reflection->{$method->getName()}() : null;");

			} elseif ($className === "ReflectionParameter" && in_array($propertyName, array("defaultValueConstant", "defaultValueConstantName"))) {
				$fromReflection->addBody("\$instance->{$propertyName} = PHP_VERSION_ID >= 50500 && \$reflection->isDefaultValueAvailable() ? \$reflection->{$method->getName()}() : null;");

			} elseif ($className === "ReflectionClass" && in_array($propertyName, array("traitNames", "trait"))) {
				$fromReflection->addBody("\$instance->{$propertyName} = PHP_VERSION_ID >= 50400 ? \$reflection->{$method->getName()}() : null;");

			} elseif ($className === "ReflectionMethod" && in_array($propertyName, array("generator"))) {
				$fromReflection->addBody("\$instance->{$propertyName} = PHP_VERSION_ID >= 50500 ? \$reflection->{$method->getName()}() : null;");

			} elseif (!$objectType) {
				$fromReflection->addBody("\$instance->{$propertyName} = \$reflection->{$method->getName()}();");
			}
		}
	}

	if ($currentAnnotationReaderMethod) {
		$class->addProperty("annotations")
			->setVisibility("private")
			->addComment("@var object[]")
			->setValue(array());

		$fromReflection->addBody("\$instance->annotations = \$reader->{$currentAnnotationReaderMethod}(\$reflection);");

		$getter = $class->addMethod("getAnnotations");
		$getter
			->addParameter("annotationClassName")
			->setDefaultValue(null)
			->setOptional(true);
		$getter
			->addComment("@param string \$annotationClassName if supplied, returns only annotations of given class name")
			->addComment("@return object[]");
		$getter
			->addBody("if (\$annotationClassName === null) {")
			->addBody("\treturn \$this->annotations;")
			->addBody("} else {\n")
			->addBody("\t\$annotations = array();")
			->addBody("\tforeach (\$this->annotations as \$annotation) {")
			->addBody("\t\tif (is_a(\$annotation, \$annotationClassName)) {")
			->addBody("\t\t\t\$annotations[] = \$annotation;")
			->addBody("\t\t}")
			->addBody("\t}")
			->addBody("\treturn \$annotations;")
			->addBody("}");

		$oneAnnotationGetter = $class->addMethod("getAnnotation");
		$oneAnnotationGetter
			->addParameter("annotationClassName");
		$oneAnnotationGetter
			->addComment("@param string \$annotationClassName")
			->addComment("@throws \\InvalidArgumentException")
			->addComment("@return object");

		$oneAnnotationGetter
			->addBody("\$annotations = \$this->getAnnotations(\$annotationClassName);")
			->addBody("if (isset(\$annotations[1])) {")
			->addBody("\tthrow new \\InvalidArgumentException('More than one annotation of class ' . \$annotationClassName . '.');")
			->addBody("} elseif (isset(\$annotations[0])) {")
			->addBody("\treturn \$annotations[0];")
			->addBody("} else {")
			->addBody("\treturn null;")
			->addBody("}");

		$hasAnnotation = $class->addMethod("hasAnnotation");
		$hasAnnotation
			->addParameter("annotationClassName");
		$hasAnnotation
			->addComment("@param string \$annotationClassName")
			->addComment("@return boolean");
		$hasAnnotation
			->addBody("return count(\$this->getAnnotations(\$annotationClassName)) > 0;");

		$setter = $class->addMethod("setAnnotations");
		$setter
			->addComment("@var \$annotations object[]")
			->addComment("@return \$this");
		$setter
			->addParameter("annotations");
		$setter
			->addBody("\$this->annotations = \$annotations;")
			->addBody("return \$this;");
	}

	if ($className === "ReflectionClass") {
		$class
			->addProperty("useStatements", array())
			->addComment("@var string[] array of lowercased alias => FQN use statements");

		$fromReflection->addBody("\$instance->useStatements = \$phpParser->parseClass(\$reflection);");
		$fromReflection->addBody("\$instance->useStatements[strtolower(\$reflection->getShortName())] = \$reflection->getName();");

		$getter = $class->addMethod("getUseStatements");
		$getter
			->addComment("@return string[] array of lowercased alias => FQN use statements");
		$getter
			->addBody("return \$this->useStatements;");

		$setter = $class->addMethod("setUseStatements");
		$setter
			->addComment("@param string[] \$useStatements")
			->addComment("@return \$this");
		$setter
			->addParameter("useStatements");
		$setter
			->addBody("\$this->useStatements = \$useStatements;")
			->addBody("return \$this;");

		$getPropertyMethod = $class->addMethod("getProperty");
		$ns->addUse("Skrz\\Meta\\Reflection\\Property", null, $propertyAlias);
		$getPropertyMethod
			->addComment("@param string \$propertyName")
			->addComment("@return {$propertyAlias}");
		$getPropertyMethod
			->addParameter("propertyName");
		$getPropertyMethod
			->addBody("foreach (\$this->getProperties() as \$property) {")
			->addBody("\tif (\$property->getName() === \$propertyName){")
			->addBody("\t\treturn \$property;")
			->addBody("\t}")
			->addBody("}")
			->addBody("return null;");

		$getMethodMethod = $class->addMethod("getMethod");
		$ns->addUse("Skrz\\Meta\\Reflection\\Method", null, $methodAlias);
		$getMethodMethod
			->addComment("@param string \$methodName")
			->addComment("@return {$methodAlias}");
		$getMethodMethod
			->addParameter("methodName");
		$getMethodMethod
			->addBody("foreach (\$this->getMethods() as \$method) {")
			->addBody("\tif (\$method->getName() === \$methodName){")
			->addBody("\t\treturn \$method;")
			->addBody("\t}")
			->addBody("}")
			->addBody("return null;");

		$isDateTime = $class->addMethod("isDateTime");
		$isDateTime
			->addBody("return is_a(\$this->getName(), \\DateTimeInterface::class, true);");
	}

	$fromReflection->addBody($endOfFromReflection->getBody());

	if ($className === "ReflectionMethod") {
		$ns->addUse("Skrz\\Meta\\Reflection\\Parameter", null, $parameterAlias);
		$getParameterMethod = $class->addMethod("getParameter");
		$getParameterMethod
			->addComment("@param string|int \$parameterName")
			->addComment("@return {$parameterAlias}");
		$getParameterMethod
			->addParameter("parameterName");
		$getParameterMethod
			->addBody("foreach (\$this->getParameters() as \$parameter) {")
			->addBody("\tif ((is_string(\$parameterName) && \$parameter->getName() === \$parameterName) || (is_int(\$parameterName) && \$parameter->getPosition() === \$parameterName)){")
			->addBody("\t\treturn \$parameter;")
			->addBody("\t}")
			->addBody("}")
			->addBody("return null;");
	}

	if ($className === "ReflectionProperty") {
		$defaultValue = $class->addProperty("defaultValue");

		$defaultValue
			->addComment("@var mixed default value");

		$getDefaultValueMethod = $class->addMethod("getDefaultValue");
		$getDefaultValueMethod
			->addComment("@return mixed");
		$getDefaultValueMethod
			->addBody("return \$this->defaultValue;");

		$setDefaultValueMethod = $class->addMethod("setDefaultValue");
		$setDefaultValueMethod
			->addComment("@param mixed \$defaultValue")
			->addComment("@return \$this");
		$setDefaultValueMethod
			->addParameter("defaultValue");
		$setDefaultValueMethod
			->addBody("\$this->defaultValue = \$defaultValue;")
			->addBody("return \$this;");

		$fromReflection
			->addBody("\$defaultProperties = \$reflection->getDeclaringClass()->getDefaultProperties();")
			->addBody("if (isset(\$defaultProperties[\$instance->name])) {")
			->addBody("\t\$instance->defaultValue = \$defaultProperties[\$instance->name];")
			->addBody("}");
	}

	if (in_array($className, array("ReflectionProperty", "ReflectionMethod", "ReflectionParameter"))) {
		$ns->addUse("Skrz\\Meta\\Reflection\\MixedType", null, $mixedTypeAlias);

		$class
			->addProperty("type")
			->addComment("@var {$mixedTypeAlias}");

		$getter = $class->addMethod("getType");
		$getter->addComment("@return {$mixedTypeAlias}");
		$getter->addBody("return \$this->type;");

		$setter = $class->addMethod("setType");
		$setter
			->addComment("@param {$mixedTypeAlias} \$type")
			->addComment("@return \$this");
		$setter
			->addParameter("type");
		$setter
			->addBody("\$this->type = \$type;")
			->addBody("return \$this;");

		if ($className === "ReflectionProperty") {
			$varRegex = "/@var\\s+([a-zA-Z0-9\\\\\\[\\]_]+)/";
			$fromReflection
				->addBody("if (preg_match(" . var_export($varRegex, true) . ", \$instance->docComment, \$m)) {\n\t\$typeString = \$m[1];\n}");

		} elseif ($className === "ReflectionMethod") {
			$returnRegex = "/@return\\s+([a-zA-Z0-9\\\\\\[\\]_]+)/";
			$fromReflection
				->addBody("if (preg_match(" . var_export($returnRegex, true) . ", \$instance->docComment, \$m)) {\n\t\$typeString = \$m[1];\n}");

		} elseif ($className === "ReflectionParameter") {
			$returnRegex = "/@param\\s+([a-zA-Z0-9\\\\\\[\\]_]+)\\s+\\\$";
			$fromReflection
				->addBody("if (preg_match(" . var_export($returnRegex, true) . " . preg_quote(\$instance->name) . '/', \$instance->declaringFunction->getDocComment(), \$m)) {\n\t\$typeString = \$m[1];\n}");
		}

		$ns->addUse("Skrz\\Meta\\Reflection\\VoidType", null, $voidTypeAlias);

		$fromReflection
			->addBody("if (isset(\$typeString)) {")
			->addBody("\t\$instance->type = {$mixedTypeAlias}::fromString(\$typeString, \$stack, \$reader, \$phpParser, \$instance->declaringClass);");

		if ($className === "ReflectionParameter") {
			$ns->addUse("Skrz\\Meta\\Reflection\\Type", null, $typeAlias);

			$fromReflection
				->addBody("} elseif (\$reflection->getClass()) {")
				->addBody("\t\$instance->type = {$typeAlias}::fromReflection(\$reflection->getClass(), \$stack, \$reader, \$phpParser, \$instance->declaringClass);")
				->addBody("} elseif (\$reflection->isArray()) {")
				->addBody("\t\$instance->type = {$mixedTypeAlias}::fromString('array', \$stack, \$reader, \$phpParser, \$instance->declaringClass);");
		}

		$fromReflection
			->addBody("} else {")
			->addBody("\t\$instance->type = " . ($className === "ReflectionMethod" ? $voidTypeAlias : $mixedTypeAlias) . "::getInstance();")
			->addBody("}");
	}

	$fromReflection
		->addBody("\nreturn \$instance;");

	if ($discoveryClassName === "Skrz\\Meta\\Reflection\\Type") {
		$class->addMethod("__toString")->addBody("return \$this->getName();");
	}

	file_put_contents(
		$outputDirectory . "/" . str_replace("\\", "/", $discoveryClassName) . ".php",
		(string)$file
	);

}
