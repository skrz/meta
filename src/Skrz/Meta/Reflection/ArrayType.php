<?php
namespace Skrz\Meta\Reflection;

class ArrayType extends MixedType
{

	/** @var MixedType */
	private $baseType;

	/** @var ArrayType[] */
	private static $arrayTypesRegistry = array();

	public static function create(MixedType $baseType)
	{
		// FIXME: potential memory leak
		$baseTypeHash = spl_object_hash($baseType);

		if (!isset(self::$arrayTypesRegistry[$baseTypeHash])) {
			self::$arrayTypesRegistry[$baseTypeHash] = new ArrayType($baseType);
		}

		return self::$arrayTypesRegistry[$baseTypeHash];
	}

	private function __construct(MixedType $baseType)
	{
		$this->baseType = $baseType;
	}

	/**
	 * @return MixedType
	 */
	public function getBaseType()
	{
		return $this->baseType;
	}

	public function isArray()
	{
		return true;
	}

	public function __toString()
	{
		return ((string)$this->baseType) . "[]";
	}

}
