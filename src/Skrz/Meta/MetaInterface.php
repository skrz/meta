<?php
namespace Skrz\Meta;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
interface MetaInterface
{

	/**
	 * Returns instance of this meta class
	 *
	 * @return $this
	 */
	public static function getInstance();

	/**
	 * Creates new instance
	 *
	 * @return object
	 */
	public static function create();

	/**
	 * Resets object's properties to their default values
	 *
	 * @param object $object
	 * @return void
	 */
	public static function reset($object);

}
