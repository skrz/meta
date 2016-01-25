<?php
namespace Skrz\Meta\Protobuf;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 *
 * @Annotation
 */
final class ProtobufField
{

	/** @var int */
	public $number;

	/** @var string */
	public $wireType;

	/** @var bool */
	public $unsigned = false;

	/** @var bool */
	public $packed = false;

}
