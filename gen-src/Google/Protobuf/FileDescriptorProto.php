<?php
namespace Google\Protobuf;

use Skrz\Meta\Protobuf\ProtobufField;

/**
 * Describes a complete .proto file.
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class FileDescriptorProto
{
	/**
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=1, wireType="string", unsigned=false, packed=false)
	 */
	protected $name;

	/**
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=2, wireType="string", unsigned=false, packed=false)
	 */
	protected $package;

	/**
	 * Names of files imported by this file.
	 *
	 * @var string[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=3, wireType="string", unsigned=false, packed=false)
	 */
	protected $dependency;

	/**
	 * Indexes of the public imported files in the dependency list above.
	 *
	 * @var int[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=10, wireType="varint", unsigned=false, packed=false)
	 */
	protected $publicDependency;

	/**
	 * Indexes of the weak imported files in the dependency list.
	 *  For Google-internal migration only. Do not use.
	 *
	 * @var int[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=11, wireType="varint", unsigned=false, packed=false)
	 */
	protected $weakDependency;

	/**
	 * All top-level definitions in this file.
	 *
	 * @var DescriptorProto[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=4, wireType="string", unsigned=false, packed=false)
	 */
	protected $messageType;

	/**
	 * @var EnumDescriptorProto[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=5, wireType="string", unsigned=false, packed=false)
	 */
	protected $enumType;

	/**
	 * @var ServiceDescriptorProto[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=6, wireType="string", unsigned=false, packed=false)
	 */
	protected $service;

	/**
	 * @var FieldDescriptorProto[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=7, wireType="string", unsigned=false, packed=false)
	 */
	protected $extension;

	/**
	 * @var FileOptions
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=8, wireType="string", unsigned=false, packed=false)
	 */
	protected $options;

	/**
	 * This field contains optional information about the original source code.
	 *  You may safely remove this entire field without harming runtime
	 *  functionality of the descriptors -- the information is needed only by
	 *  development tools.
	 *
	 * @var SourceCodeInfo
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=9, wireType="string", unsigned=false, packed=false)
	 */
	protected $sourceCodeInfo;

	/**
	 * The syntax of the proto file.
	 *  The supported values are "proto2" and "proto3".
	 *
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=12, wireType="string", unsigned=false, packed=false)
	 */
	protected $syntax;


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
	public function getPackage()
	{
		return $this->package;
	}


	/**
	 * @param string $package
	 *
	 * @return self
	 */
	public function setPackage($package)
	{
		$this->package = $package;
		return $this;
	}


	/**
	 * @return string[]
	 */
	public function getDependency()
	{
		return $this->dependency;
	}


	/**
	 * @param string[] $dependency
	 *
	 * @return self
	 */
	public function setDependency($dependency)
	{
		$this->dependency = $dependency;
		return $this;
	}


	/**
	 * @return int[]
	 */
	public function getPublicDependency()
	{
		return $this->publicDependency;
	}


	/**
	 * @param int[] $publicDependency
	 *
	 * @return self
	 */
	public function setPublicDependency($publicDependency)
	{
		$this->publicDependency = $publicDependency;
		return $this;
	}


	/**
	 * @return int[]
	 */
	public function getWeakDependency()
	{
		return $this->weakDependency;
	}


	/**
	 * @param int[] $weakDependency
	 *
	 * @return self
	 */
	public function setWeakDependency($weakDependency)
	{
		$this->weakDependency = $weakDependency;
		return $this;
	}


	/**
	 * @return DescriptorProto[]
	 */
	public function getMessageType()
	{
		return $this->messageType;
	}


	/**
	 * @param DescriptorProto[] $messageType
	 *
	 * @return self
	 */
	public function setMessageType($messageType)
	{
		$this->messageType = $messageType;
		return $this;
	}


	/**
	 * @return EnumDescriptorProto[]
	 */
	public function getEnumType()
	{
		return $this->enumType;
	}


	/**
	 * @param EnumDescriptorProto[] $enumType
	 *
	 * @return self
	 */
	public function setEnumType($enumType)
	{
		$this->enumType = $enumType;
		return $this;
	}


	/**
	 * @return ServiceDescriptorProto[]
	 */
	public function getService()
	{
		return $this->service;
	}


	/**
	 * @param ServiceDescriptorProto[] $service
	 *
	 * @return self
	 */
	public function setService($service)
	{
		$this->service = $service;
		return $this;
	}


	/**
	 * @return FieldDescriptorProto[]
	 */
	public function getExtension()
	{
		return $this->extension;
	}


	/**
	 * @param FieldDescriptorProto[] $extension
	 *
	 * @return self
	 */
	public function setExtension($extension)
	{
		$this->extension = $extension;
		return $this;
	}


	/**
	 * @return FileOptions
	 */
	public function getOptions()
	{
		return $this->options;
	}


	/**
	 * @param FileOptions $options
	 *
	 * @return self
	 */
	public function setOptions($options)
	{
		$this->options = $options;
		return $this;
	}


	/**
	 * @return SourceCodeInfo
	 */
	public function getSourceCodeInfo()
	{
		return $this->sourceCodeInfo;
	}


	/**
	 * @param SourceCodeInfo $sourceCodeInfo
	 *
	 * @return self
	 */
	public function setSourceCodeInfo($sourceCodeInfo)
	{
		$this->sourceCodeInfo = $sourceCodeInfo;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getSyntax()
	{
		return $this->syntax;
	}


	/**
	 * @param string $syntax
	 *
	 * @return self
	 */
	public function setSyntax($syntax)
	{
		$this->syntax = $syntax;
		return $this;
	}
}
