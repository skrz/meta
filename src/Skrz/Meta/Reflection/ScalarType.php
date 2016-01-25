<?php
namespace Skrz\Meta\Reflection;

class ScalarType extends MixedType
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

	public function __toString()
	{
		return "scalar";
	}

}
