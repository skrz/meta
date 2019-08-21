<?php
namespace Google\Protobuf\SourceCodeInfo;

use Skrz\Meta\Protobuf\ProtobufField;

/**
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class Location
{
	/**
	 * Identifies which part of the FileDescriptorProto was defined at this
	 *  location.
	 *
	 *  Each element is a field number or an index.  They form a path from
	 *  the root FileDescriptorProto to the place where the definition.  For
	 *  example, this path:
	 *    [ 4, 3, 2, 7, 1 ]
	 *  refers to:
	 *    file.message_type(3)  // 4, 3
	 *        .field(7)         // 2, 7
	 *        .name()           // 1
	 *  This is because FileDescriptorProto.message_type has field number 4:
	 *    repeated DescriptorProto message_type = 4;
	 *  and DescriptorProto.field has field number 2:
	 *    repeated FieldDescriptorProto field = 2;
	 *  and FieldDescriptorProto.name has field number 1:
	 *    optional string name = 1;
	 *
	 *  Thus, the above path gives the location of a field name.  If we removed
	 *  the last element:
	 *    [ 4, 3, 2, 7 ]
	 *  this path refers to the whole field declaration (from the beginning
	 *  of the label to the terminating semicolon).
	 *
	 * @var int[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=1, wireType="varint", unsigned=false, packed=true)
	 */
	protected $path;

	/**
	 * Always has exactly three or four elements: start line, start column,
	 *  end line (optional, otherwise assumed same as start line), end column.
	 *  These are packed into a single field for efficiency.  Note that line
	 *  and column numbers are zero-based -- typically you will want to add
	 *  1 to each before displaying to a user.
	 *
	 * @var int[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=2, wireType="varint", unsigned=false, packed=true)
	 */
	protected $span;

	/**
	 * If this SourceCodeInfo represents a complete declaration, these are any
	 *  comments appearing before and after the declaration which appear to be
	 *  attached to the declaration.
	 *
	 *  A series of line comments appearing on consecutive lines, with no other
	 *  tokens appearing on those lines, will be treated as a single comment.
	 *
	 *  leading_detached_comments will keep paragraphs of comments that appear
	 *  before (but not connected to) the current element. Each paragraph,
	 *  separated by empty lines, will be one comment element in the repeated
	 *  field.
	 *
	 *  Only the comment content is provided; comment markers (e.g. //) are
	 *  stripped out.  For block comments, leading whitespace and an asterisk
	 *  will be stripped from the beginning of each line other than the first.
	 *  Newlines are included in the output.
	 *
	 *  Examples:
	 *
	 *    optional int32 foo = 1;  // Comment attached to foo.
	 *    // Comment attached to bar.
	 *    optional int32 bar = 2;
	 *
	 *    optional string baz = 3;
	 *    // Comment attached to baz.
	 *    // Another line attached to baz.
	 *
	 *    // Comment attached to qux.
	 *    //
	 *    // Another line attached to qux.
	 *    optional double qux = 4;
	 *
	 *    // Detached comment for corge. This is not leading or trailing comments
	 *    // to qux or corge because there are blank lines separating it from
	 *    // both.
	 *
	 *    // Detached comment for corge paragraph 2.
	 *
	 *    optional string corge = 5;
	 *    /* Block comment attached
	 *     * to corge.  Leading asterisks
	 *     * will be removed. * /
	 *    /* Block comment attached to
	 *     * grault. * /
	 *    optional int32 grault = 6;
	 *
	 *    // ignored detached comments.
	 *
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=3, wireType="string", unsigned=false, packed=false)
	 */
	protected $leadingComments;

	/**
	 * @var string
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=4, wireType="string", unsigned=false, packed=false)
	 */
	protected $trailingComments;

	/**
	 * @var string[]
	 *
	 * @Skrz\Meta\Protobuf\ProtobufField(number=6, wireType="string", unsigned=false, packed=false)
	 */
	protected $leadingDetachedComments;


	/**
	 * @return int[]
	 */
	public function getPath()
	{
		return $this->path;
	}


	/**
	 * @param int[] $path
	 *
	 * @return self
	 */
	public function setPath($path)
	{
		$this->path = $path;
		return $this;
	}


	/**
	 * @return int[]
	 */
	public function getSpan()
	{
		return $this->span;
	}


	/**
	 * @param int[] $span
	 *
	 * @return self
	 */
	public function setSpan($span)
	{
		$this->span = $span;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getLeadingComments()
	{
		return $this->leadingComments;
	}


	/**
	 * @param string $leadingComments
	 *
	 * @return self
	 */
	public function setLeadingComments($leadingComments)
	{
		$this->leadingComments = $leadingComments;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getTrailingComments()
	{
		return $this->trailingComments;
	}


	/**
	 * @param string $trailingComments
	 *
	 * @return self
	 */
	public function setTrailingComments($trailingComments)
	{
		$this->trailingComments = $trailingComments;
		return $this;
	}


	/**
	 * @return string[]
	 */
	public function getLeadingDetachedComments()
	{
		return $this->leadingDetachedComments;
	}


	/**
	 * @param string[] $leadingDetachedComments
	 *
	 * @return self
	 */
	public function setLeadingDetachedComments($leadingDetachedComments)
	{
		$this->leadingDetachedComments = $leadingDetachedComments;
		return $this;
	}
}
