<?php
namespace Skrz\Meta\Fixtures\Protobuf;

use Skrz\Meta\Protobuf\ProtobufField;

/**
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class ClassWithFixed64Property
{
	/**
	 * @var int
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=1, wireType="fixed64", unsigned=true, packed=false)
	 */
	protected $x;


	/**
	 * @return int
	 */
	public function getX()
	{
		return $this->x;
	}


	/**
	 * @param int $x
	 *
	 * @return self
	 */
	public function setX($x)
	{
		$this->x = $x;
		return $this;
	}
}
