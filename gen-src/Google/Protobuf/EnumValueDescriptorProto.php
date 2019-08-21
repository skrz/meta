<?php
namespace Google\Protobuf;

use Skrz\Meta\Protobuf\ProtobufField;

/**
 * Describes a value within an enum.
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class EnumValueDescriptorProto
{
	/**
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=1, wireType="string", unsigned=false, packed=false)
	 */
	protected $name;

	/**
	 * @var int
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=2, wireType="varint", unsigned=false, packed=false)
	 */
	protected $number;

	/**
	 * @var EnumValueOptions
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=3, wireType="string", unsigned=false, packed=false)
	 */
	protected $options;


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @param string $name
	 *
	 * @return self
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}


	/**
	 * @return int
	 */
	public function getNumber()
	{
		return $this->number;
	}


	/**
	 * @param int $number
	 *
	 * @return self
	 */
	public function setNumber($number)
	{
		$this->number = $number;
		return $this;
	}


	/**
	 * @return EnumValueOptions
	 */
	public function getOptions()
	{
		return $this->options;
	}


	/**
	 * @param EnumValueOptions $options
	 *
	 * @return self
	 */
	public function setOptions($options)
	{
		$this->options = $options;
		return $this;
	}
}
