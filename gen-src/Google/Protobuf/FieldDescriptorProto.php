<?php
namespace Google\Protobuf;

use Google\Protobuf\FieldDescriptorProto\LabelEnum;
use Google\Protobuf\FieldDescriptorProto\TypeEnum;
use Skrz\Meta\Protobuf\ProtobufField;

/**
 * Describes a field within a message.
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class FieldDescriptorProto
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
	 * @Skrz\Meta\Protobuf\ProtobufField(number=3, wireType="varint", unsigned=false, packed=false)
	 */
	protected $number;

	/**
	 * @var int
	 * @see LabelEnum
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=4, wireType="varint", unsigned=false, packed=false)
	 */
	protected $label;

	/**
	 * If type_name is set, this need not be set.  If both this and type_name
	 *  are set, this must be one of TYPE_ENUM, TYPE_MESSAGE or TYPE_GROUP.
	 *
	 * @var int
	 * @see TypeEnum
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=5, wireType="varint", unsigned=false, packed=false)
	 */
	protected $type;

	/**
	 * For message and enum types, this is the name of the type.  If the name
	 *  starts with a '.', it is fully-qualified.  Otherwise, C++-like scoping
	 *  rules are used to find the type (i.e. first the nested types within this
	 *  message are searched, then within the parent, on up to the root
	 *  namespace).
	 *
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=6, wireType="string", unsigned=false, packed=false)
	 */
	protected $typeName;

	/**
	 * For extensions, this is the name of the type being extended.  It is
	 *  resolved in the same manner as type_name.
	 *
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=2, wireType="string", unsigned=false, packed=false)
	 */
	protected $extendee;

	/**
	 * For numeric types, contains the original text representation of the value.
	 *  For booleans, "true" or "false".
	 *  For strings, contains the default text contents (not escaped in any way).
	 *  For bytes, contains the C escaped value.  All bytes >= 128 are escaped.
	 *  TODO(kenton):  Base-64 encode?
	 *
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=7, wireType="string", unsigned=false, packed=false)
	 */
	protected $defaultValue;

	/**
	 * If set, gives the index of a oneof in the containing type's oneof_decl
	 *  list.  This field is a member of that oneof.
	 *
	 * @var int
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=9, wireType="varint", unsigned=false, packed=false)
	 */
	protected $oneofIndex;

	/**
	 * JSON name of this field. The value is set by protocol compiler. If the
	 *  user has set a "json_name" option on this field, that option's value
	 *  will be used. Otherwise, it's deduced from the field's name by converting
	 *  it to camelCase.
	 *
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=10, wireType="string", unsigned=false, packed=false)
	 */
	protected $jsonName;

	/**
	 * @var FieldOptions
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=8, wireType="string", unsigned=false, packed=false)
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
	 * @return int
	 */
	public function getLabel()
	{
		return $this->label;
	}


	/**
	 * @param int $label
	 *
	 * @return self
	 */
	public function setLabel($label)
	{
		$this->label = $label;
		return $this;
	}


	/**
	 * @return int
	 */
	public function getType()
	{
		return $this->type;
	}


	/**
	 * @param int $type
	 *
	 * @return self
	 */
	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getTypeName()
	{
		return $this->typeName;
	}


	/**
	 * @param string $typeName
	 *
	 * @return self
	 */
	public function setTypeName($typeName)
	{
		$this->typeName = $typeName;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getExtendee()
	{
		return $this->extendee;
	}


	/**
	 * @param string $extendee
	 *
	 * @return self
	 */
	public function setExtendee($extendee)
	{
		$this->extendee = $extendee;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getDefaultValue()
	{
		return $this->defaultValue;
	}


	/**
	 * @param string $defaultValue
	 *
	 * @return self
	 */
	public function setDefaultValue($defaultValue)
	{
		$this->defaultValue = $defaultValue;
		return $this;
	}


	/**
	 * @return int
	 */
	public function getOneofIndex()
	{
		return $this->oneofIndex;
	}


	/**
	 * @param int $oneofIndex
	 *
	 * @return self
	 */
	public function setOneofIndex($oneofIndex)
	{
		$this->oneofIndex = $oneofIndex;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getJsonName()
	{
		return $this->jsonName;
	}


	/**
	 * @param string $jsonName
	 *
	 * @return self
	 */
	public function setJsonName($jsonName)
	{
		$this->jsonName = $jsonName;
		return $this;
	}


	/**
	 * @return FieldOptions
	 */
	public function getOptions()
	{
		return $this->options;
	}


	/**
	 * @param FieldOptions $options
	 *
	 * @return self
	 */
	public function setOptions($options)
	{
		$this->options = $options;
		return $this;
	}
}
