<?php
namespace Google\Protobuf\UninterpretedOption;

use Skrz\Meta\Protobuf\ProtobufField;

/**
 * The name of the uninterpreted option.  Each string represents a segment in
 *  a dot-separated name.  is_extension is true iff a segment represents an
 *  extension (denoted with parentheses in options specs in .proto files).
 *  E.g.,{ ["foo", false], ["bar.baz", true], ["qux", false] } represents
 *  "foo.(bar.baz).qux".
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class NamePart
{
	/**
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=1, wireType="string", unsigned=false, packed=false)
	 */
	protected $namePart;

	/**
	 * @var bool
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=2, wireType="varint", unsigned=false, packed=false)
	 */
	protected $isExtension;


	/**
	 * @return string
	 */
	public function getNamePart()
	{
		return $this->namePart;
	}


	/**
	 * @param string $namePart
	 *
	 * @return self
	 */
	public function setNamePart($namePart)
	{
		$this->namePart = $namePart;
		return $this;
	}


	/**
	 * @return bool
	 */
	public function getIsExtension()
	{
		return $this->isExtension;
	}


	/**
	 * @param bool $isExtension
	 *
	 * @return self
	 */
	public function setIsExtension($isExtension)
	{
		$this->isExtension = $isExtension;
		return $this;
	}
}
