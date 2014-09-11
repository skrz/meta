<?php
namespace Skrz\Meta\JSON;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 *
 * @Annotation
 */
final class JsonDiscriminatorProperty
{

	/** @var string */
	public $name;

	/** @var string */
	public $group = JsonProperty::DEFAULT_GROUP;

}
