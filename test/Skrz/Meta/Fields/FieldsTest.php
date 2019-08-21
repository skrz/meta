<?php
namespace Skrz\Meta\Fields;

use PHPUnit\Framework\TestCase;

class FieldsTest extends TestCase
{

	public function testFromArrayNoArg()
	{
		$fields = Fields::fromArray();
		$this->assertCount(0, $fields);
	}

	public function testFromArrayEmptyArray()
	{
		$fields = Fields::fromArray([

		]);

		$this->assertCount(0, $fields);
	}

	public function testFromArraySingleField()
	{
		$fields = Fields::fromArray(["foo" => true]);

		$this->assertCount(1, $fields);
		$this->assertTrue($fields->field("foo"));
		$this->assertFalse($fields->field("bar"));
		$this->assertCount(0, $fields->fields("foo"));
	}

	public function testFromArrayMultipleFields()
	{
		$fields = Fields::fromArray(["foo" => true, "bar" => 1]);

		$this->assertCount(2, $fields);
		$this->assertTrue($fields->field("foo"));
		$this->assertTrue($fields->field("bar"));
		$this->assertFalse($fields->field("baz"));
		$this->assertCount(0, $fields->fields("foo"));
		$this->assertCount(0, $fields->fields("bar"));
	}

	public function testFromArraySingleNestedField()
	{
		$fields = Fields::fromArray(["foo" => ["bar" => 1]]);

		$this->assertCount(1, $fields);
		$this->assertTrue($fields->field("foo"));
		$this->assertFalse($fields->field("bar"));
		$this->assertCount(1, $fields->fields("foo"));
		$this->assertTrue($fields->fields("foo")->field("bar"));
		$this->assertCount(0, $fields->fields("foo")->fields("bar"));
	}

	public function testFromArrayMultipleNestedFields()
	{
		$fields = Fields::fromArray(["foo" => ["bar" => 1], "baz" => ["qux" => true]]);

		$this->assertCount(2, $fields);
		$this->assertTrue($fields->field("foo"));
		$this->assertFalse($fields->field("bar"));
		$this->assertTrue($fields->field("baz"));
		$this->assertFalse($fields->field("qux"));

		$this->assertCount(1, $fields->fields("foo"));
		$this->assertTrue($fields->fields("foo")->field("bar"));
		$this->assertCount(0, $fields->fields("foo")->fields("bar"));

		$this->assertCount(1, $fields->fields("baz"));
		$this->assertTrue($fields->fields("baz")->field("qux"));
		$this->assertCount(0, $fields->fields("baz")->fields("qux"));
	}

	public function testFromStringEmptyString()
	{
		$fields = Fields::fromString("");

		$this->assertCount(0, $fields);
		$this->assertFalse($fields->field("foo"));
	}

	public function testFromStringSingleField()
	{
		$fields = Fields::fromString("foo");

		$this->assertCount(1, $fields);
		$this->assertTrue($fields->field("foo"));
		$this->assertCount(0, $fields->fields("foo"));
		$this->assertFalse($fields->field("bar"));
	}

	public function testFromStringMultipleFields()
	{
		$fields = Fields::fromString("foo,bar");

		$this->assertCount(2, $fields);
		$this->assertTrue($fields->field("foo"));
		$this->assertCount(0, $fields->fields("foo"));
		$this->assertTrue($fields->field("bar"));
		$this->assertCount(0, $fields->fields("bar"));
		$this->assertFalse($fields->field("baz"));
	}

	public function testFromStringMultipleFieldsWhitespace()
	{
		$fields = Fields::fromString("  foo\t\t,  bar  \t");

		$this->assertCount(2, $fields);
		$this->assertTrue($fields->field("foo"));
		$this->assertCount(0, $fields->fields("foo"));
		$this->assertTrue($fields->field("bar"));
		$this->assertCount(0, $fields->fields("bar"));
		$this->assertFalse($fields->field("baz"));
	}

	public function testFromStringDotNestedSingleField()
	{
		$fields = Fields::fromString("foo.bar");

		$this->assertCount(1, $fields);
		$this->assertTrue($fields->field("foo"));
		$this->assertCount(1, $fields->fields("foo"));
		$this->assertTrue($fields->fields("foo")->field("bar"));
		$this->assertCount(0, $fields->fields("foo")->fields("bar"));
	}

	public function testFromStringDotNestedMultipleFields()
	{
		$fields = Fields::fromString("foo.bar,baz.qux,baz.kwanza");

		$this->assertCount(2, $fields);

		$this->assertTrue($fields->field("foo"));
		$this->assertCount(1, $fields->fields("foo"));
		$this->assertTrue($fields->fields("foo")->field("bar"));
		$this->assertCount(0, $fields->fields("foo")->fields("bar"));

		$this->assertTrue($fields->field("baz"));
		$this->assertCount(2, $fields->fields("baz"));
		$this->assertTrue($fields->fields("baz")->field("qux"));
		$this->assertCount(0, $fields->fields("baz")->fields("qux"));
		$this->assertTrue($fields->fields("baz")->field("kwanza"));
		$this->assertCount(0, $fields->fields("baz")->fields("kwanza"));
	}

	public function testFromStringBraceNestedSingleField()
	{
		$fields = Fields::fromString("foo{bar}");

		$this->assertCount(1, $fields);
		$this->assertTrue($fields->field("foo"));
		$this->assertCount(1, $fields->fields("foo"));
		$this->assertTrue($fields->fields("foo")->field("bar"));
		$this->assertCount(0, $fields->fields("foo")->fields("bar"));
	}

	public function testFromStringBraceNestedMultipleFields()
	{
		$fields = Fields::fromString("foo{bar},baz{qux,kwanza}");

		$this->assertCount(2, $fields);

		$this->assertTrue($fields->field("foo"));
		$this->assertCount(1, $fields->fields("foo"));
		$this->assertTrue($fields->fields("foo")->field("bar"));
		$this->assertCount(0, $fields->fields("foo")->fields("bar"));

		$this->assertTrue($fields->field("baz"));
		$this->assertCount(2, $fields->fields("baz"));
		$this->assertTrue($fields->fields("baz")->field("qux"));
		$this->assertCount(0, $fields->fields("baz")->fields("qux"));
		$this->assertTrue($fields->fields("baz")->field("kwanza"));
		$this->assertCount(0, $fields->fields("baz")->fields("kwanza"));
	}

	public function testFromStringBraceDeepNestedField()
	{
		$fields = Fields::fromString("foo{bar{baz{qux}}}");

		$this->assertCount(1, $fields);
		$this->assertTrue($fields->field("foo"));
		$this->assertCount(1, $fields->fields("foo"));
		$this->assertTrue($fields->fields("foo")->field("bar"));
		$this->assertCount(1, $fields->fields("foo")->fields("bar"));
		$this->assertTrue($fields->fields("foo")->fields("bar")->field("baz"));
		$this->assertCount(1, $fields->fields("foo")->fields("bar")->fields("baz"));
		$this->assertTrue($fields->fields("foo")->fields("bar")->fields("baz")->field("qux"));
		$this->assertCount(0, $fields->fields("foo")->fields("bar")->fields("baz")->fields("qux"));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testFromStringNoCommaAfterRightBrace()
	{
		Fields::fromString("foo{bar}baz");
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testFromStringUnbalancedBraces()
	{
		Fields::fromString("foo{bar");
	}

	/**
	 * @dataProvider fieldsStrings
	 */
	public function testToString($input, $expected)
	{
		$this->assertEquals($expected, (string)Fields::fromString($input));
	}

	public function fieldsStrings()
	{
		return [
			["", ""],
			["foo,bar", "foo,bar"],
			["  foo\t\t,  bar  \t", "foo,bar"],
			["foo.bar", "foo{bar}"],
			["foo.bar,baz.qux,baz.kwanza", "foo{bar},baz{qux,kwanza}"],
			["foo{bar}", "foo{bar}"],
			["foo{bar},baz{qux,kwanza}", "foo{bar},baz{qux,kwanza}"],
			["foo{bar{baz{qux}}}", "foo{bar{baz{qux}}}"],
		];
	}

}
