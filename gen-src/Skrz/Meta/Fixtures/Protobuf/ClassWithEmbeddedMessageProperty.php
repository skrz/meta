<?php
namespace Skrz\Meta\Fixtures\Protobuf;

use Skrz\Meta\Fixtures\Protobuf\ClassWithEmbeddedMessageProperty\Embedded;
use Skrz\Meta\Protobuf\ProtobufField;

/**
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class ClassWithEmbeddedMessageProperty
{
	/**
	 * @var Embedded
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=1, wireType="string", unsigned=false, packed=false)
	 */
	protected $x;


	/**
	 * @return Embedded
	 */
	public function getX()
	{
		return $this->x;
	}


	/**
	 * @param Embedded $x
	 *
	 * @return self
	 */
	public function setX($x)
	{
		$this->x = $x;
		return $this;
	}
}
