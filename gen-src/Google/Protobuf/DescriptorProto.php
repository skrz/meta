<?php
namespace Google\Protobuf;

use Google\Protobuf\DescriptorProto\ExtensionRange;
use Google\Protobuf\DescriptorProto\ReservedRange;
use Skrz\Meta\Protobuf\ProtobufField;

/**
 * Describes a message type.
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class DescriptorProto
{
	/**
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=1, wireType="string", unsigned=false, packed=false)
	 */
	protected $name;

	/**
	 * @var FieldDescriptorProto[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=2, wireType="string", unsigned=false, packed=false)
	 */
	protected $field;

	/**
	 * @var FieldDescriptorProto[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=6, wireType="string", unsigned=false, packed=false)
	 */
	protected $extension;

	/**
	 * @var DescriptorProto[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=3, wireType="string", unsigned=false, packed=false)
	 */
	protected $nestedType;

	/**
	 * @var EnumDescriptorProto[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=4, wireType="string", unsigned=false, packed=false)
	 */
	protected $enumType;

	/**
	 * @var ExtensionRange[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=5, wireType="string", unsigned=false, packed=false)
	 */
	protected $extensionRange;

	/**
	 * @var OneofDescriptorProto[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=8, wireType="string", unsigned=false, packed=false)
	 */
	protected $oneofDecl;

	/**
	 * @var MessageOptions
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=7, wireType="string", unsigned=false, packed=false)
	 */
	protected $options;

	/**
	 * @var ReservedRange[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=9, wireType="string", unsigned=false, packed=false)
	 */
	protected $reservedRange;

	/**
	 * Reserved field names, which may not be used by fields in the same message.
	 *  A given name may only be reserved once.
	 *
	 * @var string[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=10, wireType="string", unsigned=false, packed=false)
	 */
	protected $reservedName;


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
	 * @return FieldDescriptorProto[]
	 */
	public function getField()
	{
		return $this->field;
	}


	/**
	 * @param FieldDescriptorProto[] $field
	 *
	 * @return self
	 */
	public function setField($field)
	{
		$this->field = $field;
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
	 * @return DescriptorProto[]
	 */
	public function getNestedType()
	{
		return $this->nestedType;
	}


	/**
	 * @param DescriptorProto[] $nestedType
	 *
	 * @return self
	 */
	public function setNestedType($nestedType)
	{
		$this->nestedType = $nestedType;
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
	 * @return ExtensionRange[]
	 */
	public function getExtensionRange()
	{
		return $this->extensionRange;
	}


	/**
	 * @param ExtensionRange[] $extensionRange
	 *
	 * @return self
	 */
	public function setExtensionRange($extensionRange)
	{
		$this->extensionRange = $extensionRange;
		return $this;
	}


	/**
	 * @return OneofDescriptorProto[]
	 */
	public function getOneofDecl()
	{
		return $this->oneofDecl;
	}


	/**
	 * @param OneofDescriptorProto[] $oneofDecl
	 *
	 * @return self
	 */
	public function setOneofDecl($oneofDecl)
	{
		$this->oneofDecl = $oneofDecl;
		return $this;
	}


	/**
	 * @return MessageOptions
	 */
	public function getOptions()
	{
		return $this->options;
	}


	/**
	 * @param MessageOptions $options
	 *
	 * @return self
	 */
	public function setOptions($options)
	{
		$this->options = $options;
		return $this;
	}


	/**
	 * @return ReservedRange[]
	 */
	public function getReservedRange()
	{
		return $this->reservedRange;
	}


	/**
	 * @param ReservedRange[] $reservedRange
	 *
	 * @return self
	 */
	public function setReservedRange($reservedRange)
	{
		$this->reservedRange = $reservedRange;
		return $this;
	}


	/**
	 * @return string[]
	 */
	public function getReservedName()
	{
		return $this->reservedName;
	}


	/**
	 * @param string[] $reservedName
	 *
	 * @return self
	 */
	public function setReservedName($reservedName)
	{
		$this->reservedName = $reservedName;
		return $this;
	}
}
