<?php
namespace Google\Protobuf;

use Google\Protobuf\FieldOptions\CTypeEnum;
use Google\Protobuf\FieldOptions\JSTypeEnum;
use Skrz\Meta\Protobuf\ProtobufField;

/**
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class FieldOptions
{
	/**
	 * The ctype option instructs the C++ code generator to use a different
	 *  representation of the field than it normally would.  See the specific
	 *  options below.  This option is not yet implemented in the open source
	 *  release -- sorry, we'll try to include it in a future version!
	 *
	 * @var int
	 * @see CTypeEnum
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=1, wireType="varint", unsigned=false, packed=false)
	 */
	protected $ctype;

	/**
	 * The packed option can be enabled for repeated primitive fields to enable
	 *  a more efficient representation on the wire. Rather than repeatedly
	 *  writing the tag and type for each element, the entire array is encoded as
	 *  a single length-delimited blob. In proto3, only explicit setting it to
	 *  false will avoid using packed encoding.
	 *
	 * @var bool
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=2, wireType="varint", unsigned=false, packed=false)
	 */
	protected $packed;

	/**
	 * The jstype option determines the JavaScript type used for values of the
	 *  field.  The option is permitted only for 64 bit integral and fixed types
	 *  (int64, uint64, sint64, fixed64, sfixed64).  By default these types are
	 *  represented as JavaScript strings.  This avoids loss of precision that can
	 *  happen when a large value is converted to a floating point JavaScript
	 *  numbers.  Specifying JS_NUMBER for the jstype causes the generated
	 *  JavaScript code to use the JavaScript "number" type instead of strings.
	 *  This option is an enum to permit additional types to be added,
	 *  e.g. goog.math.Integer.
	 *
	 * @var int
	 * @see JSTypeEnum
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=6, wireType="varint", unsigned=false, packed=false)
	 */
	protected $jstype;

	/**
	 * Should this field be parsed lazily?  Lazy applies only to message-type
	 *  fields.  It means that when the outer message is initially parsed, the
	 *  inner message's contents will not be parsed but instead stored in encoded
	 *  form.  The inner message will actually be parsed when it is first accessed.
	 *
	 *  This is only a hint.  Implementations are free to choose whether to use
	 *  eager or lazy parsing regardless of the value of this option.  However,
	 *  setting this option true suggests that the protocol author believes that
	 *  using lazy parsing on this field is worth the additional bookkeeping
	 *  overhead typically needed to implement it.
	 *
	 *  This option does not affect the public interface of any generated code;
	 *  all method signatures remain the same.  Furthermore, thread-safety of the
	 *  interface is not affected by this option; const methods remain safe to
	 *  call from multiple threads concurrently, while non-const methods continue
	 *  to require exclusive access.
	 *
	 *
	 *  Note that implementations may choose not to check required fields within
	 *  a lazy sub-message.  That is, calling IsInitialized() on the outher message
	 *  may return true even if the inner message has missing required fields.
	 *  This is necessary because otherwise the inner message would have to be
	 *  parsed in order to perform the check, defeating the purpose of lazy
	 *  parsing.  An implementation which chooses not to check required fields
	 *  must be consistent about it.  That is, for any particular sub-message, the
	 *  implementation must either *always* check its required fields, or *never*
	 *  check its required fields, regardless of whether or not the message has
	 *  been parsed.
	 *
	 * @var bool
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=5, wireType="varint", unsigned=false, packed=false)
	 */
	protected $lazy;

	/**
	 * Is this field deprecated?
	 *  Depending on the target platform, this can emit Deprecated annotations
	 *  for accessors, or it will be completely ignored; in the very least, this
	 *  is a formalization for deprecating fields.
	 *
	 * @var bool
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=3, wireType="varint", unsigned=false, packed=false)
	 */
	protected $deprecated;

	/**
	 * For Google-internal migration only. Do not use.
	 *
	 * @var bool
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=10, wireType="varint", unsigned=false, packed=false)
	 */
	protected $weak;

	/**
	 * The parser stores options it doesn't recognize here. See above.
	 *
	 * @var UninterpretedOption[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=999, wireType="string", unsigned=false, packed=false)
	 */
	protected $uninterpretedOption;


	/**
	 * @return int
	 */
	public function getCtype()
	{
		return $this->ctype;
	}


	/**
	 * @param int $ctype
	 *
	 * @return self
	 */
	public function setCtype($ctype)
	{
		$this->ctype = $ctype;
		return $this;
	}


	/**
	 * @return bool
	 */
	public function getPacked()
	{
		return $this->packed;
	}


	/**
	 * @param bool $packed
	 *
	 * @return self
	 */
	public function setPacked($packed)
	{
		$this->packed = $packed;
		return $this;
	}


	/**
	 * @return int
	 */
	public function getJstype()
	{
		return $this->jstype;
	}


	/**
	 * @param int $jstype
	 *
	 * @return self
	 */
	public function setJstype($jstype)
	{
		$this->jstype = $jstype;
		return $this;
	}


	/**
	 * @return bool
	 */
	public function getLazy()
	{
		return $this->lazy;
	}


	/**
	 * @param bool $lazy
	 *
	 * @return self
	 */
	public function setLazy($lazy)
	{
		$this->lazy = $lazy;
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
	public function getWeak()
	{
		return $this->weak;
	}


	/**
	 * @param bool $weak
	 *
	 * @return self
	 */
	public function setWeak($weak)
	{
		$this->weak = $weak;
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
