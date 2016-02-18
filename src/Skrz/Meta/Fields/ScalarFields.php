<?php
namespace Skrz\Meta\Fields;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
class ScalarFields implements FieldsBuilderInterface
{

	/**
	 * @var ScalarFields
	 */
	private static $instance;

	private function __construct()
	{
	}

	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new ScalarFields();
		}

		return self::$instance;
	}

	public function appendField($fieldName)
	{
		$map = new MapFields();
		return $map->appendFields($fieldName, $this);
	}

	public function appendFields($fieldName, FieldsBuilderInterface $fields)
	{
		$map = new MapFields();
		return $map->appendFields($fieldName, $fields);
	}

	public function build()
	{
		return $this;
	}

	public function field($fieldName)
	{
		return false;
	}

	public function fields($fieldName)
	{
		return $this;
	}

	public function count()
	{
		return 0;
	}

	public function builder($fieldName = null)
	{
		return $this;
	}

	public function current()
	{
		return null;
	}

	public function next()
	{
	}

	public function key()
	{
		return null;
	}

	public function valid()
	{
		return false;
	}

	public function rewind()
	{
	}

	public function offsetExists($offset)
	{
		return $this->field($offset);
	}

	public function offsetGet($offset)
	{
		return $this->fields($offset);
	}

	public function offsetSet($offset, $value)
	{
		throw new \LogicException("Cannot set field '{$offset}'.");
	}

	public function offsetUnset($offset)
	{
		throw new \LogicException("Cannot unset field '{$offset}'.");
	}

	public function __toString()
	{
		return "";
	}

}
