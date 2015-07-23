<?php
namespace Skrz\Meta\Fixtures\JSON;

use Skrz\Meta\JSON\ArrayOfJsonRoot;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 *
 * @ArrayOfJsonRoot
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
