<?php
namespace Google\Protobuf;

use Google\Protobuf\UninterpretedOption\NamePart;
use Skrz\Meta\Protobuf\ProtobufField;

/**
 * A message representing a option the parser does not recognize. This only
 *  appears in options protos created by the compiler::Parser class.
 *  DescriptorPool resolves these when building Descriptor objects. Therefore,
 *  options protos in descriptor objects (e.g. returned by Descriptor::options(),
 *  or produced by Descriptor::CopyTo()) will never have UninterpretedOptions
 *  in them.
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class UninterpretedOption
{
	/**
	 * @var NamePart[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=2, wireType="string", unsigned=false, packed=false)
	 */
	protected $name;

	/**
	 * The value of the uninterpreted option, in whatever type the tokenizer
	 *  identified it as during parsing. Exactly one of these should be set.
	 *
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=3, wireType="string", unsigned=false, packed=false)
	 */
	protected $identifierValue;

	/**
	 * @var int
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=4, wireType="varint", unsigned=true, packed=false)
	 */
	protected $positiveIntValue;

	/**
	 * @var int
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=5, wireType="varint", unsigned=true, packed=false)
	 */
	protected $negativeIntValue;

	/**
	 * @var float
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=6, wireType="fixed64", unsigned=true, packed=false)
	 */
	protected $doubleValue;

	/**
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=7, wireType="string", unsigned=true, packed=false)
	 */
	protected $stringValue;

	/**
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=8, wireType="string", unsigned=true, packed=false)
	 */
	protected $aggregateValue;


	/**
	 * @return NamePart[]
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @param NamePart[] $name
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
	public function getIdentifierValue()
	{
		return $this->identifierValue;
	}


	/**
	 * @param string $identifierValue
	 *
	 * @return self
	 */
	public function setIdentifierValue($identifierValue)
	{
		$this->identifierValue = $identifierValue;
		return $this;
	}


	/**
	 * @return int
	 */
	public function getPositiveIntValue()
	{
		return $this->positiveIntValue;
	}


	/**
	 * @param int $positiveIntValue
	 *
	 * @return self
	 */
	public function setPositiveIntValue($positiveIntValue)
	{
		$this->positiveIntValue = $positiveIntValue;
		return $this;
	}


	/**
	 * @return int
	 */
	public function getNegativeIntValue()
	{
		return $this->negativeIntValue;
	}


	/**
	 * @param int $negativeIntValue
	 *
	 * @return self
	 */
	public function setNegativeIntValue($negativeIntValue)
	{
		$this->negativeIntValue = $negativeIntValue;
		return $this;
	}


	/**
	 * @return float
	 */
	public function getDoubleValue()
	{
		return $this->doubleValue;
	}


	/**
	 * @param float $doubleValue
	 *
	 * @return self
	 */
	public function setDoubleValue($doubleValue)
	{
		$this->doubleValue = $doubleValue;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getStringValue()
	{
		return $this->stringValue;
	}


	/**
	 * @param string $stringValue
	 *
	 * @return self
	 */
	public function setStringValue($stringValue)
	{
		$this->stringValue = $stringValue;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getAggregateValue()
	{
		return $this->aggregateValue;
	}


	/**
	 * @param string $aggregateValue
	 *
	 * @return self
	 */
	public function setAggregateValue($aggregateValue)
	{
		$this->aggregateValue = $aggregateValue;
		return $this;
	}
}
