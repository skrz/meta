<?php
namespace Google\Protobuf;

use Skrz\Meta\Protobuf\ProtobufField;

/**
 * Describes an enum type.
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class EnumDescriptorProto
{
	/**
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=1, wireType="string", unsigned=false, packed=false)
	 */
	protected $name;

	/**
	 * @var EnumValueDescriptorProto[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=2, wireType="string", unsigned=false, packed=false)
	 */
	protected $value;

	/**
	 * @var EnumOptions
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
	 * @return EnumValueDescriptorProto[]
	 */
	public function getValue()
	{
		return $this->value;
	}


	/**
	 * @param EnumValueDescriptorProto[] $value
	 *
	 * @return self
	 */
	public function setValue($value)
	{
		$this->value = $value;
		return $this;
	}


	/**
	 * @return EnumOptions
	 */
	public function getOptions()
	{
		return $this->options;
	}


	/**
	 * @param EnumOptions $options
	 *
	 * @return self
	 */
	public function setOptions($options)
	{
		$this->options = $options;
		return $this;
	}
}
