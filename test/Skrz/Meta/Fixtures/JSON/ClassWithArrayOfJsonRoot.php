<?php
namespace Skrz\Meta\Fixtures\JSON;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
class ClassWithArrayOfJsonRoot
{

	/**
	 * @var string
	 */
	public $direct;

	/**
	 * @var ClassWithPublicProperty
	 */
	public $nested;

	/**
	 * @var string[]
	 */
	public $arrayOfStrings;

}
