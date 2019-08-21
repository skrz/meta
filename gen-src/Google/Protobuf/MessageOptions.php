<?php
namespace Google\Protobuf;

use Skrz\Meta\Protobuf\ProtobufField;

/**
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class MessageOptions
{
	/**
	 * Set true to use the old proto1 MessageSet wire format for extensions.
	 *  This is provided for backwards-compatibility with the MessageSet wire
	 *  format.  You should not use this for any other reason:  It's less
	 *  efficient, has fewer features, and is more complicated.
	 *
	 *  The message must be defined exactly as follows:
	 *    message Foo {
	 *      option message_set_wire_format = true;
	 *      extensions 4 to max;
	 *    }
	 *  Note that the message cannot have any defined fields; MessageSets only
	 *  have extensions.
	 *
	 *  All extensions of your type must be singular messages; e.g. they cannot
	 *  be int32s, enums, or repeated messages.
	 *
	 *  Because this is an option, the above two restrictions are not enforced by
	 *  the protocol compiler.
	 *
	 * @var bool
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=1, wireType="varint", unsigned=false, packed=false)
	 */
	protected $messageSetWireFormat;

	/**
	 * Disables the generation of the standard "descriptor()" accessor, which can
	 *  conflict with a field of the same name.  This is meant to make migration
	 *  from proto1 easier; new code should avoid fields named "descriptor".
	 *
	 * @var bool
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=2, wireType="varint", unsigned=false, packed=false)
	 */
	protected $noStandardDescriptorAccessor;

	/**
	 * Is this message deprecated?
	 *  Depending on the target platform, this can emit Deprecated annotations
	 *  for the message, or it will be completely ignored; in the very least,
	 *  this is a formalization for deprecating messages.
	 *
	 * @var bool
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=3, wireType="varint", unsigned=false, packed=false)
	 */
	protected $deprecated;

	/**
	 * Whether the message is an automatically generated map entry type for the
	 *  maps field.
	 *
	 *  For maps fields:
	 *      map<KeyType, ValueType> map_field = 1;
	 *  The parsed descriptor looks like:
	 *      message MapFieldEntry {
	 *          option map_entry = true;
	 *          optional KeyType key = 1;
	 *          optional ValueType value = 2;
	 *      }
	 *      repeated MapFieldEntry map_field = 1;
	 *
	 *  Implementations may choose not to generate the map_entry=true message, but
	 *  use a native map in the target language to hold the keys and values.
	 *  The reflection APIs in such implementions still need to work as
	 *  if the field is a repeated message field.
	 *
	 *  NOTE: Do not set the option in .proto files. Always use the maps syntax
	 *  instead. The option should only be implicitly set by the proto compiler
	 *  parser.
	 *
	 * @var bool
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=7, wireType="varint", unsigned=false, packed=false)
	 */
	protected $mapEntry;

	/**
	 * The parser stores options it doesn't recognize here. See above.
	 *
	 * @var UninterpretedOption[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=999, wireType="string", unsigned=false, packed=false)
	 */
	protected $uninterpretedOption;


	/**
	 * @return bool
	 */
	public function getMessageSetWireFormat()
	{
		return $this->messageSetWireFormat;
	}


	/**
	 * @param bool $messageSetWireFormat
	 *
	 * @return self
	 */
	public function setMessageSetWireFormat($messageSetWireFormat)
	{
		$this->messageSetWireFormat = $messageSetWireFormat;
		return $this;
	}


	/**
	 * @return bool
	 */
	public function getNoStandardDescriptorAccessor()
	{
		return $this->noStandardDescriptorAccessor;
	}


	/**
	 * @param bool $noStandardDescriptorAccessor
	 *
	 * @return self
	 */
	public function setNoStandardDescriptorAccessor($noStandardDescriptorAccessor)
	{
		$this->noStandardDescriptorAccessor = $noStandardDescriptorAccessor;
		return $this;
	}


	/**
	 * @return bool
	 */
	public function getDeprecated()
	{
		return $this->deprecated;
	}


	/**
	 * @param bool $deprecated
	 *
	 * @return self
	 */
	public function setDeprecated($deprecated)
	{
		$this->deprecated = $deprecated;
		return $this;
	}


	/**
	 * @return bool
	 */
	public function getMapEntry()
	{
		return $this->mapEntry;
	}


	/**
	 * @param bool $mapEntry
	 *
	 * @return self
	 */
	public function setMapEntry($mapEntry)
	{
		$this->mapEntry = $mapEntry;
		return $this;
	}


	/**
	 * @return UninterpretedOption[]
	 */
	public function getUninterpretedOption()
	{
		return $this->uninterpretedOption;
	}


	/**
	 * @param UninterpretedOption[] $uninterpretedOption
	 *
	 * @return self
	 */
	public function setUninterpretedOption($uninterpretedOption)
	{
		$this->uninterpretedOption = $uninterpretedOption;
		return $this;
	}
}
