<?php
namespace Google\Protobuf\Compiler\CodeGeneratorResponse;

use Skrz\Meta\Protobuf\ProtobufField;

/**
 * Represents a single generated file.
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class File
{
	/**
	 * The file name, relative to the output directory.  The name must not
	 *  contain "." or ".." components and must be relative, not be absolute (so,
	 *  the file cannot lie outside the output directory).  "/" must be used as
	 *  the path separator, not "\".
	 *
	 *  If the name is omitted, the content will be appended to the previous
	 *  file.  This allows the generator to break large files into small chunks,
	 *  and allows the generated text to be streamed back to protoc so that large
	 *  files need not reside completely in memory at one time.  Note that as of
	 *  this writing protoc does not optimize for this -- it will read the entire
	 *  CodeGeneratorResponse before writing files to disk.
	 *
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=1, wireType="string", unsigned=false, packed=false)
	 */
	protected $name;

	/**
	 * If non-empty, indicates that the named file should already exist, and the
	 *  content here is to be inserted into that file at a defined insertion
	 *  point.  This feature allows a code generator to extend the output
	 *  produced by another code generator.  The original generator may provide
	 *  insertion points by placing special annotations in the file that look
	 *  like:
	 *    @@protoc_insertion_point(NAME)
	 *  The annotation can have arbitrary text before and after it on the line,
	 *  which allows it to be placed in a comment.  NAME should be replaced with
	 *  an identifier naming the point -- this is what other generators will use
	 *  as the insertion_point.  Code inserted at this point will be placed
	 *  immediately above the line containing the insertion point (thus multiple
	 *  insertions to the same point will come out in the order they were added).
	 *  The double-@ is intended to make it unlikely that the generated code
	 *  could contain things that look like insertion points by accident.
	 *
	 *  For example, the C++ code generator places the following line in the
	 *  .pb.h files that it generates:
	 *    // @@protoc_insertion_point(namespace_scope)
	 *  This line appears within the scope of the file's package namespace, but
	 *  outside of any particular class.  Another plugin can then specify the
	 *  insertion_point "namespace_scope" to generate additional classes or
	 *  other declarations that should be placed in this scope.
	 *
	 *  Note that if the line containing the insertion point begins with
	 *  whitespace, the same whitespace will be added to every line of the
	 *  inserted text.  This is useful for languages like Python, where
	 *  indentation matters.  In these languages, the insertion point comment
	 *  should be indented the same amount as any inserted code will need to be
	 *  in order to work correctly in that context.
	 *
	 *  The code generator that generates the initial file and the one which
	 *  inserts into it must both run as part of a single invocation of protoc.
	 *  Code generators are executed in the order in which they appear on the
	 *  command line.
	 *
	 *  If |insertion_point| is present, |name| must also be present.
	 *
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=2, wireType="string", unsigned=false, packed=false)
	 */
	protected $insertionPoint;

	/**
	 * The file contents.
	 *
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=15, wireType="string", unsigned=false, packed=false)
	 */
	protected $content;


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @param string $name
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
	public function getInsertionPoint()
	{
		return $this->insertionPoint;
	}


	/**
	 * @param string $insertionPoint
	 *
	 * @return self
	 */
	public function setInsertionPoint($insertionPoint)
	{
		$this->insertionPoint = $insertionPoint;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}


	/**
	 * @param string $content
	 *
	 * @return self
	 */
	public function setContent($content)
	{
		$this->content = $content;
		return $this;
	}
}
