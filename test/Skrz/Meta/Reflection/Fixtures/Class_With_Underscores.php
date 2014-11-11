<?php
namespace Skrz\Meta\Reflection\Fixtures;

class Class_With_Underscores
{

	/**
	 * @var Class_With_Underscores
	 */
	private static $instance;

	/**
	 * @return Class_With_Underscores
	 */
	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}
