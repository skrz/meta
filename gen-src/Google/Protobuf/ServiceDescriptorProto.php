<?php
namespace Google\Protobuf;

use Skrz\Meta\Protobuf\ProtobufField;

/**
 * Describes a service.
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class ServiceDescriptorProto
{
	/**
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=1, wireType="string", unsigned=false, packed=false)
	 */
	protected $name;

	/**
	 * @var MethodDescriptorProto[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=2, wireType="string", unsigned=false, packed=false)
	 */
	protected $method;

	/**
	 * @var ServiceOptions
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
	 * @return MethodDescriptorProto[]
	 */
	public function getMethod()
	{
		return $this->method;
	}


	/**
	 * @param MethodDescriptorProto[] $method
	 *
	 * @return self
	 */
	public function setMethod($method)
	{
		$this->method = $method;
		return $this;
	}


	/**
	 * @return ServiceOptions
	 */
	public function getOptions()
	{
		return $this->options;
	}


	/**
	 * @param ServiceOptions $options
	 *
	 * @return self
	 */
	public function setOptions($options)
	{
		$this->options = $options;
		return $this;
	}
}
