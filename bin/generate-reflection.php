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
use Skrz\Meta\Reflection\VoidType;

$classes = array(
	"ReflectionClass" => "Skrz\\Meta\\Reflection\\Type",
	"ReflectionMethod" => "Skrz\\Meta\\Reflection\\Method",
	"ReflectionProperty" => "Skrz\\Meta\\Reflection\\Property",
	"ReflectionParameter" => "Skrz\\Meta\\Reflection\\Parameter"
);

$someInstance = array(
	"ReflectionClass" => new \ReflectionClass(AClass::class),
	"ReflectionMethod" => $rm = new \ReflectionMethod(AClass::class, "aMethod"),
	"ReflectionProperty" => new \ReflectionProperty(AClass::class, "aProperty"),
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
		$ns->addUse(ObjectType::class, null, $mixedTypeAlias);
		$class->addExtend(ObjectType::class);
	}

	$rc = new \ReflectionClass($className);

	$someInstanceOfClassName = $someInstance[$className];
	$currentAnnotationReaderMethod = $annotationReaderMethod[$className];

	foreach ($rc->getProperties() as $property) {
		$class->addProperty($property->getName())
			->addDocument("@var string");
	}

	$class->addMethod("__construct")
		->setBody("");

	$fromReflection = $class->addMethod("fromReflection");
	$fromReflection
		->setStatic(true);

	$ns->addUse($className, null, $alias);
	$fromReflection->addParameter("reflection")
		->setTypeHint($alias)
		->setDefaultValue(null)
		->setOptional(true);

	$fromReflection
		->addBody("if (\$reflection === null) {\n\treturn null;\n}\n")
		->addBody("if (func_num_args() > 1) {\n\t\$stack = func_get_arg(1);\n} else {\n\t\$stack = new \\ArrayObject();\n}\n")
		->addBody("\$stackExpression = {$stackExpression[$className]};\n")
		->addBody("if (isset(\$stack[\$stackExpression])) {\n\treturn \$stack[\$stackExpression];\n}\n")
		->addBody("\$stack[\$stackExpression] = \$instance = new {$class->getName()}();\n");

	$ns->addUse(AnnotationReader::class, null, $annotationReaderAlias);
	$fromReflection->addBody("if (func_num_args() > 2) {\n\t\$reader = func_get_arg(2);\n} else {\n\t\$reader = new {$annotationReaderAlias}();\n}\n");

	$ns->addUse(PhpParser::class, null, $phpParserAlias);
	$fromReflection->addBody("if (func_num_args() > 3) {\n\t\$phpParser = func_get_arg(3);\n} else {\n\t\$phpParser = new {$phpParserAlias}();\n}\n");

	$endOfFromReflection = new \Nette\PhpGenerator\Method();

	foreach ($rc->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
		$is = false;

		if (
			$method->getName() !== "getTraitAliases" &&
			$method->getName() !== "getStaticProperties" &&
			$method->getName() !== "isCloneable" &&
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

			$properties = $class->getProperties();
			if (isset($properties[$propertyName])) {
				$property = $properties[$propertyName];
			} else {
				$property = $class->addProperty($propertyName)
					->setVisibility("private");
			}

			$property
				->setDocuments(array("@var {$returnType}{$arrayType}"));

			$getter = $class->addMethod(($is ? "is" : "get") . ucfirst($propertyName));
			$getter
				->addDocument("@return {$returnType}{$arrayType}");

			$getter
				->setBody("return \$this->{$propertyName};");

			$setter = $class->addMethod("set" . ucfirst($propertyName));

			$setter
				->addDocument("@param {$returnType}{$arrayType} \${$propertyName}")
				->addDocument("@return \$this");

			$setter
				->addParameter($propertyName);

			$setter
				->addBody("\$this->{$propertyName} = \${$propertyName};")
				->addBody("return \$this;");


			if ($objectType) {
				if ($arrayType) {
					$endOfFromReflection->addBody("\$instance->{$propertyName} = array();");
					$endOfFromReflection->addBody(
						"foreach (\$reflection->{$method->getName()}() as \$key => \$value) {\n" .
						"\t\$instance->{$propertyName}[\$key] = {$returnType}::fromReflection(\$value, \$stack, \$reader, \$phpParser);\n" .
						"}"
					);
				} elseif ($className === "ReflectionMethod" && $propertyName === "prototype") {
					$endOfFromReflection->addBody("try {\n");
					$endOfFromReflection->addBody("\t\$instance->{$propertyName} = \$reflection->{$method->getName()}();");
					$endOfFromReflection->addBody("} catch (\\ReflectionException \$e) {\n");
					$endOfFromReflection->addBody("\t\$instance->{$propertyName} = null;\n");
					$endOfFromReflection->addBody("}\n");

				} else {
					$endOfFromReflection->addBody("\$instance->{$propertyName} = {$returnType}::fromReflection(\$reflection->{$method->getName()}() ? \$reflection->{$method->getName()}() : null, \$stack, \$reader, \$phpParser);");
				}

			} elseif ($className === "ReflectionParameter" && in_array($propertyName, array("defaultValue", "defaultValueConstant", "defaultValueConstantName"))) {
				$fromReflection->addBody("\$instance->{$propertyName} = \$reflection->isDefaultValueAvailable() ? \$reflection->{$method->getName()}() : null;");

			} else {
				$fromReflection->addBody("\$instance->{$propertyName} = \$reflection->{$method->getName()}();");
			}
		}
	}

	if ($currentAnnotationReaderMethod) {
		$ns->addUse(Annotation::class, null, $alias);
		$class->addProperty("annotations")
			->setVisibility("private")
			->addDocument("@var {$alias}[]")
			->setValue(array());

		$fromReflection->addBody("\$instance->annotations = \$reader->{$currentAnnotationReaderMethod}(\$reflection);");

		$getter = $class->addMethod("getAnnotations");
		$getter
			->addParameter("annotationClassName")
			->setDefaultValue(null)
			->setOptional(true);
		$getter
			->addDocument("@param string \$annotationClassName if supplied, returns only annotations of given class name")
			->addDocument("@return {$alias}[]");
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
			->addDocument("@param string \$annotationClassName")
			->addDocument("@throws \\InvalidArgumentException")
			->addDocument("@return {$alias}");

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
			->addDocument("@param string \$annotationClassName")
			->addDocument("@return boolean");
		$hasAnnotation
			->addBody("return count(\$this->getAnnotations(\$annotationClassName)) > 0;");

		$setter = $class->addMethod("setAnnotations");
		$setter
			->addDocument("@var \$annotations {$alias}[]")
			->addDocument("@return \$this");
		$setter
			->addParameter("annotations");
		$setter
			->addBody("\$this->annotations = \$annotations;")
			->addBody("return \$this;");
	}

	if ($className === "ReflectionClass") {
		$class
			->addProperty("useStatements", array())
			->addDocument("@var string[] array of lowercased alias => FQN use statements");

		$fromReflection->addBody("\$instance->useStatements = \$phpParser->parseClass(\$reflection);");
		$fromReflection->addBody("\$instance->useStatements[strtolower(\$reflection->getShortName())] = \$reflection->getName();");

		$getter = $class->addMethod("getUseStatements");
		$getter
			->addDocument("@return string[] array of lowercased alias => FQN use statements");
		$getter
			->addBody("return \$this->useStatements;");

		$setter = $class->addMethod("setUseStatements");
		$setter
			->addDocument("@param string[] \$useStatements")
			->addDocument("@return \$this");
		$setter
			->addParameter("useStatements");
		$setter
			->addBody("\$this->useStatements = \$useStatements;")
			->addBody("return \$this;");

		$getPropertyMethod = $class->addMethod("getProperty");
		$ns->addUse(Property::class, null, $propertyAlias);
		$getPropertyMethod
			->addDocument("@param string \$propertyName")
			->addDocument("@return {$propertyAlias}");
		$getPropertyMethod
			->addParameter("propertyName");
		$getPropertyMethod
			->addBody("foreach (\$this->properties as \$property) {")
			->addBody("\tif (\$property->getName() === \$propertyName){")
			->addBody("\t\treturn \$property;")
			->addBody("\t}")
			->addBody("}")
			->addBody("return null;");

		$getMethodMethod = $class->addMethod("getMethod");
		$ns->addUse(Method::class, null, $methodAlias);
		$getMethodMethod
			->addDocument("@param string \$methodName")
			->addDocument("@return {$methodAlias}");
		$getMethodMethod
			->addParameter("methodName");
		$getMethodMethod
			->addBody("foreach (\$this->methods as \$method) {")
			->addBody("\tif (\$method->getName() === \$methodName){")
			->addBody("\t\treturn \$method;")
			->addBody("\t}")
			->addBody("}")
			->addBody("return null;");
	}

	$fromReflection->addBody($endOfFromReflection->getBody());

	if ($className === "ReflectionMethod") {
		$ns->addUse(Parameter::class, null, $parameterAlias);
		$getParameterMethod = $class->addMethod("getParameter");
		$getParameterMethod
			->addDocument("@param string|int \$parameterName")
			->addDocument("@return {$parameterAlias}");
		$getParameterMethod
			->addParameter("parameterName");
		$getParameterMethod
			->addBody("foreach (\$this->parameters as \$parameter) {")
			->addBody("\tif ((is_string(\$parameterName) && \$parameter->getName() === \$parameterName) || (is_int(\$parameterName) && \$parameter->getPosition() === \$parameterName)){")
			->addBody("\t\treturn \$parameter;")
			->addBody("\t}")
			->addBody("}")
			->addBody("return null;");
	}

	if ($className === "ReflectionProperty") {
		$defaultValue = $class->addProperty("defaultValue");

		$defaultValue
			->addDocument("@var mixed default value");

		$getDefaultValueMethod = $class->addMethod("getDefaultValue");
		$getDefaultValueMethod
			->addDocument("@return mixed");
		$getDefaultValueMethod
			->addBody("return \$this->defaultValue;");

		$setDefaultValueMethod = $class->addMethod("setDefaultValue");
		$setDefaultValueMethod
			->addDocument("@param mixed \$defaultValue")
			->addDocument("@return \$this");
		$setDefaultValueMethod
			->addParameter("defaultValue");
		$setDefaultValueMethod
			->addBody("\$this->defaultValue = \$defaultValue;")
			->addBody("return \$this;");

		$fromReflection
			->addBody("\$defaultProperties = \$instance->declaringClass->getDefaultProperties();")
			->addBody("if (isset(\$defaultProperties[\$instance->name])) {")
			->addBody("\t\$instance->defaultValue = \$defaultProperties[\$instance->name];")
			->addBody("}");
	}

	if (in_array($className, array("ReflectionProperty", "ReflectionMethod", "ReflectionParameter"))) {
		$ns->addUse(MixedType::class, null, $mixedTypeAlias);

		$class
			->addProperty("type")
			->addDocument("@var {$mixedTypeAlias}");

		$getter = $class->addMethod("getType");
		$getter->addDocument("@return {$mixedTypeAlias}");
		$getter->addBody("return \$this->type;");

		$setter = $class->addMethod("setType");
		$setter
			->addDocument("@param {$mixedTypeAlias} \$type")
			->addDocument("@return \$this");
		$setter
			->addParameter("type");
		$setter
			->addBody("\$this->type = \$type;")
			->addBody("return \$this;");

		if ($className === "ReflectionProperty") {
			$varRegex = "/@var\\s+([a-zA-Z0-9\\\\\\[\\]]+)/";
			$fromReflection
				->addBody("if (preg_match(" . var_export($varRegex, true) . ", \$instance->docComment, \$m)) {\n\t\$typeString = \$m[1];\n}");

		} elseif ($className === "ReflectionMethod") {
			$returnRegex = "/@return\\s+([a-zA-Z0-9\\\\\\[\\]]+)/";
			$fromReflection
				->addBody("if (preg_match(" . var_export($returnRegex, true) . ", \$instance->docComment, \$m)) {\n\t\$typeString = \$m[1];\n}");

		} elseif ($className === "ReflectionParameter") {
			$returnRegex = "/@param\\s+([a-zA-Z0-9\\\\\\[\\]]+)\\s+\\\$";
			$fromReflection
				->addBody("if (preg_match(" . var_export($returnRegex, true) . " . preg_quote(\$instance->name) . '/', \$instance->declaringFunction->getDocComment(), \$m)) {\n\t\$typeString = \$m[1];\n}");
		}

		$ns->addUse(VoidType::class, null, $voidTypeAlias);

		$fromReflection
			->addBody("if (isset(\$typeString)) {")
			->addBody("\t\$instance->type = {$mixedTypeAlias}::fromString(\$typeString, \$stack, \$reader, \$phpParser, \$instance->declaringClass);")
			->addBody("} else {")
			->addBody("\t\$instance->type = " . ($className === "ReflectionMethod" ? $voidTypeAlias : $mixedTypeAlias) . "::getInstance();")
			->addBody("}");
	}

	$fromReflection
		->addBody("\nreturn \$instance;");

	file_put_contents(
		$outputDirectory . "/" . str_replace("\\", "/", $discoveryClassName) . ".php",
		(string)$file
	);

}
