<?php
namespace Skrz\Meta\JSON;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 *
 * @Annotation
 */
final class JsonProperty
{

	const DEFAULT_GROUP = null;

	/** @var string */
	public $name;

	/** @var string */
	public $group = JsonProperty::DEFAULT_GROUP;

	/** @var boolean */
	public $ignoreNull = true;

}
