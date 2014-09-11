<?php
namespace Skrz\Meta\PHP;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 *
 * @Annotation
 */
final class PhpDiscriminatorOffset
{

	/** @var string */
	public $offset;

	/** @var string */
	public $group = PhpArrayOffset::DEFAULT_GROUP;

}
