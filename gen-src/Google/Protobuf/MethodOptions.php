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
final class MethodOptions
{
	/**
	 * Is this method deprecated?
	 *  Depending on the target platform, this can emit Deprecated annotations
	 *  for the method, or it will be completely ignored; in the very least,
	 *  this is a formalization for deprecating methods.
	 *
	 * @var bool
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=33, wireType="varint", unsigned=false, packed=false)
	 */
	protected $deprecated;

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
