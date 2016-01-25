<?php
namespace Skrz\Meta\Reflection;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\PhpParser;

class MixedType
{

	/** @var MixedType */
	private static $instance;

	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Use getInstance() method
	 */
	private function __construct()
	{

	}

	/**
	 * Type string
	 *
	 * @param string $string
	 * @throws \InvalidArgumentException
	 * @return ArrayType|BoolType|CallableType|FloatType|IntType|MixedType|ResourceType|StringType|Type
	 */
	public static function fromString($string)
	{
		$lowercaseString = trim(strtolower($string), "\\");

		// TODO: variant types

		if ($lowercaseString === "mixed") {
			return MixedType::getInstance();

		} elseif ($lowercaseString === "scalar") {
			return ScalarType::getInstance();

		} elseif ($lowercaseString === "object") {
			return ObjectType::getInstance();

		} elseif ($lowercaseString === "void" || $lowercaseString === "null") {
			return VoidType::getInstance();

		} elseif ($lowercaseString === "numeric" || $lowercaseString === "number") {
			return NumericType::getInstance();

		} elseif ($lowercaseString === "int" || $lowercaseString === "integer") {
			return IntType::getInstance();

		} elseif ($lowercaseString === "float" || $lowercaseString === "double") {
			return FloatType::getInstance();

		} elseif ($lowercaseString === "bool" || $lowercaseString === "boolean") {
			return BoolType::getInstance();

		} elseif ($lowercaseString === "string") {
			return StringType::getInstance();

		} elseif ($lowercaseString === "resource" || $lowercaseString === "stream") {
			return ResourceType::getInstance();

		} elseif ($lowercaseString === "callable" || $lowercaseString === "callback" || trim($lowercaseString, "\\") === "closure") {
			return CallableType::getInstance();

		} elseif (strncmp($lowercaseString, "array", 5 /* strlen("array") */) === 0) {
			return ArrayType::create(MixedType::getInstance());

		} else {
			if (func_num_args() > 1) {
				$stack = func_get_arg(1);
			} else {
				$stack = new \ArrayObject();
			}

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

			if (func_num_args() > 4 && func_get_arg(4) !== null) {
				/** @var Type $declaringType */
				$declaringType = func_get_arg(4);
				$useStatements = $declaringType->getUseStatements();
			} else {
				$declaringType = null;
				$useStatements = array();
			}

			if ($lowercaseString === "\$this" || $lowercaseString === "self" || $lowercaseString === "static") {
				if ($declaringType === null) {
					throw new \InvalidArgumentException("Type string references declaring class, but no declaring class given.");
				}

				return $declaringType;

			} elseif (substr($string, -2) === "[]") {
				$baseString = substr($string, 0, strlen($string) - 2);
				return ArrayType::create(MixedType::fromString($baseString, $stack, $reader, $phpParser, $declaringType));

			} else {
				if ($string[0] === "\\") {
					$typeName = trim($string, "\\");

				} elseif (isset($useStatements[$lowercaseString])) {
					$typeName = $useStatements[$lowercaseString];
					// TODO: `use` with namespace (e.g. `use Doctrine\Mapping as ORM;`)

				} elseif ($declaringType !== null) {
					$typeName = $declaringType->getNamespaceName() . "\\" . $string;

				} else {
					$typeName = $string;
				}

				return Type::fromReflection(new \ReflectionClass($typeName), $stack, $reader, $phpParser);
			}
		}
	}

	public function isArray()
	{
		return false;
	}

	public function __toString()
	{
		return "mixed";
	}

}
