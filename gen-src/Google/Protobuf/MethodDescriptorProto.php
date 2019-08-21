<?php
namespace Google\Protobuf;

use Skrz\Meta\Protobuf\ProtobufField;

/**
 * Describes a method of a service.
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class MethodDescriptorProto
{
	/**
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=1, wireType="string", unsigned=false, packed=false)
	 */
	protected $name;

	/**
	 * Input and output type names.  These are resolved in the same way as
	 *  FieldDescriptorProto.type_name, but must refer to a message type.
	 *
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=2, wireType="string", unsigned=false, packed=false)
	 */
	protected $inputType;

	/**
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=3, wireType="string", unsigned=false, packed=false)
	 */
	protected $outputType;

	/**
	 * @var MethodOptions
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=4, wireType="string", unsigned=false, packed=false)
	 */
	protected $options;

	/**
	 * Identifies if client streams multiple client messages
	 *
	 * @var bool
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=5, wireType="varint", unsigned=false, packed=false)
	 */
	protected $clientStreaming;

	/**
	 * Identifies if server streams multiple server messages
	 *
	 * @var bool
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=6, wireType="varint", unsigned=false, packed=false)
	 */
	protected $serverStreaming;


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
	 * @return string
	 */
	public function getInputType()
	{
		return $this->inputType;
	}


	/**
	 * @param string $inputType
	 *
	 * @return self
	 */
	public function setInputType($inputType)
	{
		$this->inputType = $inputType;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getOutputType()
	{
		return $this->outputType;
	}


	/**
	 * @param string $outputType
	 *
	 * @return self
	 */
	public function setOutputType($outputType)
	{
		$this->outputType = $outputType;
		return $this;
	}


	/**
	 * @return MethodOptions
	 */
	public function getOptions()
	{
		return $this->options;
	}


	/**
	 * @param MethodOptions $options
	 *
	 * @return self
	 */
	public function setOptions($options)
	{
		$this->options = $options;
		return $this;
	}


	/**
	 * @return bool
	 */
	public function getClientStreaming()
	{
		return $this->clientStreaming;
	}


	/**
	 * @param bool $clientStreaming
	 *
	 * @return self
	 */
	public function setClientStreaming($clientStreaming)
	{
		$this->clientStreaming = $clientStreaming;
		return $this;
	}


	/**
	 * @return bool
	 */
	public function getServerStreaming()
	{
		return $this->serverStreaming;
	}


	/**
	 * @param bool $serverStreaming
	 *
	 * @return self
	 */
	public function setServerStreaming($serverStreaming)
	{
		$this->serverStreaming = $serverStreaming;
		return $this;
	}
}
