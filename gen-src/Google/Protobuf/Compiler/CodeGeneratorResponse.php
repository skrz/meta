<?php
namespace Google\Protobuf\Compiler;

use Google\Protobuf\Compiler\CodeGeneratorResponse\File;
use Skrz\Meta\Protobuf\ProtobufField;

/**
 * The plugin writes an encoded CodeGeneratorResponse to stdout.
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class CodeGeneratorResponse
{
	/**
	 * Error message.  If non-empty, code generation failed.  The plugin process
	 *  should exit with status code zero even if it reports an error in this way.
	 *
	 *  This should be used to indicate errors in .proto files which prevent the
	 *  code generator from generating correct code.  Errors which indicate a
	 *  problem in protoc itself -- such as the input CodeGeneratorRequest being
	 *  unparseable -- should be reported by writing a message to stderr and
	 *  exiting with a non-zero status code.
	 *
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=1, wireType="string", unsigned=false, packed=false)
	 */
	protected $error;

	/**
	 * @var File[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=15, wireType="string", unsigned=false, packed=false)
	 */
	protected $file;


	/**
	 * @return string
	 */
	public function getError()
	{
		return $this->error;
	}


	/**
	 * @param string $error
	 *
	 * @return self
	 */
	public function setError($error)
	{
		$this->error = $error;
		return $this;
	}


	/**
	 * @return File[]
	 */
	public function getFile()
	{
		return $this->file;
	}


	/**
	 * @param File[] $file
	 *
	 * @return self
	 */
	public function setFile($file)
	{
		$this->file = $file;
		return $this;
	}
}
