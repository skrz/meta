<?php
namespace Google\Protobuf;

use Skrz\Meta\Protobuf\ProtobufField;

/**
 * The protocol compiler can output a FileDescriptorSet containing the .proto
 *  files it parses.
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class FileDescriptorSet
{
	/**
	 * @var FileDescriptorProto[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=1, wireType="string", unsigned=false, packed=false)
	 */
	protected $file;


	/**
	 * @return FileDescriptorProto[]
	 */
	public function getFile()
	{
		return $this->file;
	}


	/**
	 * @param FileDescriptorProto[] $file
	 *
	 * @return self
	 */
	public function setFile($file)
	{
		$this->file = $file;
		return $this;
	}
}
