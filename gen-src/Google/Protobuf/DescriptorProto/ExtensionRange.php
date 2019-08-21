<?php
namespace Google\Protobuf\DescriptorProto;

use Skrz\Meta\Protobuf\ProtobufField;

/**
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class ExtensionRange
{
	/**
	 * @var int
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=1, wireType="varint", unsigned=false, packed=false)
	 */
	protected $start;

	/**
	 * @var int
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=2, wireType="varint", unsigned=false, packed=false)
	 */
	protected $end;


	/**
	 * @return int
	 */
	public function getStart()
	{
		return $this->start;
	}


	/**
	 * @param int $start
	 *
	 * @return self
	 */
	public function setStart($start)
	{
		$this->start = $start;
		return $this;
	}


	/**
	 * @return int
	 */
	public function getEnd()
	{
		return $this->end;
	}


	/**
	 * @param int $end
	 *
	 * @return self
	 */
	public function setEnd($end)
	{
		$this->end = $end;
		return $this;
	}
}
