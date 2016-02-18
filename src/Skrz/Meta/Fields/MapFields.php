<?php
namespace Skrz\Meta\Fields;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
class MapFields implements FieldsBuilderInterface
{

	/** @var FieldsBuilderInterface[] */
	private $map = [];

	public function appendField($fieldName)
	{
		$this->map[$fieldName] = ScalarFields::getInstance();
		return $this;
	}

	public function appendFields($fieldName, FieldsBuilderInterface $fields)
	{
		$this->map[$fieldName] = $fields;
		return $this;
	}

	public function build()
	{
		return $this;
	}

	public function field($fieldName)
	{
		return isset($this->map[$fieldName]);
	}

	public function fields($fieldName)
	{
		if (isset($this->map[$fieldName])) {
			return $this->map[$fieldName];
		}

		return ScalarFields::getInstance();
	}

	public function count()
	{
		return count($this->map);
	}

	public function builder($fieldName = null)
	{
		if ($fieldName === null) {
			return $this;
		} else if (isset($this->map[$fieldName])) {
			return $this->map[$fieldName];
		}

		return ScalarFields::getInstance();
	}

	public function current()
	{
		return current($this->map);
	}

	public function next()
	{
		next($this->map);
	}

	public function key()
	{
		return key($this->map);
	}

	public function valid()
	{
		return !!$this->current();
	}

	public function rewind()
	{
		reset($this->map);
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
		$s = "";

		foreach ($this->map as $k => $v) {
			if (!empty($s)) {
				$s .= ",";
			}

			$s .= $k;
			if (count($v)) {
				$s .= "{" . $v->__toString() . "}";
			}
		}

		return $s;
	}

}
