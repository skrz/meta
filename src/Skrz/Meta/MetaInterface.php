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
	 *
	 * @return void
	 */
	public static function reset($object);

	/**
	 * Computes hash of an object
	 *
	 * If $algoOrCtx is algo string name, creates hash context using hash_init(), then hashes object properties
	 * including all sub-objects and finally returns resulting hash using hash_final().
	 *
	 * If $algoOrCtx is hash resource, only calls hash_update(), does not return anything.
	 *
	 * @param object $object
	 * @param string|resource $algoOrCtx
	 * @param bool $raw
	 *
	 * @return string|void
	 */
	public static function hash($object, $algoOrCtx = "md5", $raw = false);

}
