<?php
namespace Skrz\Meta\JSON;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 *
 * @Annotation
 */
final class JsonDiscriminatorMap
{

	/** @var string[] */
	public $map;

	/** @var string */
	public $group = JsonProperty::DEFAULT_GROUP;

}
