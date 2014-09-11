<?php
namespace Skrz\Meta\PHP;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 *
 * @Annotation
 */
final class PhpDiscriminatorMap
{

	/** @var string[] */
	public $map;

	/** @var string */
	public $group = PhpArrayOffset::DEFAULT_GROUP;

}
