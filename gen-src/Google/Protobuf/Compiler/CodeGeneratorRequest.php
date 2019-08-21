<?php
namespace Google\Protobuf\Compiler;

use Google\Protobuf\FileDescriptorProto;
use Skrz\Meta\Protobuf\ProtobufField;

/**
 * An encoded CodeGeneratorRequest is written to the plugin's stdin.
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class CodeGeneratorRequest
{
	/**
	 * The .proto files that were explicitly listed on the command-line.  The
	 *  code generator should generate code only for these files.  Each file's
	 *  descriptor will be included in proto_file, below.
	 *
	 * @var string[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=1, wireType="string", unsigned=false, packed=false)
	 */
	protected $fileToGenerate;

	/**
	 * The generator parameter passed on the command-line.
	 *
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=2, wireType="string", unsigned=false, packed=false)
	 */
	protected $parameter;

	/**
	 * FileDescriptorProtos for all files in files_to_generate and everything
	 *  they import.  The files will appear in topological order, so each file
	 *  appears before any file that imports it.
	 *
	 *  protoc guarantees that all proto_files will be written after
	 *  the fields above, even though this is not technically guaranteed by the
	 *  protobuf wire format.  This theoretically could allow a plugin to stream
	 *  in the FileDescriptorProtos and handle them one by one rather than read
	 *  the entire set into memory at once.  However, as of this writing, this
	 *  is not similarly optimized on protoc's end -- it will store all fields in
	 *  memory at once before sending them to the plugin.
	 *
	 * @var FileDescriptorProto[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=15, wireType="string", unsigned=false, packed=false)
	 */
	protected $protoFile;


	/**
	 * @return string[]
	 */
	public function getFileToGenerate()
	{
		return $this->fileToGenerate;
	}


	/**
	 * @param string[] $fileToGenerate
	 *
	 * @return self
	 */
	public function setFileToGenerate($fileToGenerate)
	{
		$this->fileToGenerate = $fileToGenerate;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getParameter()
	{
		return $this->parameter;
	}


	/**
	 * @param string $parameter
	 *
	 * @return self
	 */
	public function setParameter($parameter)
	{
		$this->parameter = $parameter;
		return $this;
	}


	/**
	 * @return FileDescriptorProto[]
	 */
	public function getProtoFile()
	{
		return $this->protoFile;
	}


	/**
	 * @param FileDescriptorProto[] $protoFile
	 *
	 * @return self
	 */
	public function setProtoFile($protoFile)
	{
		$this->protoFile = $protoFile;
		return $this;
	}
}
