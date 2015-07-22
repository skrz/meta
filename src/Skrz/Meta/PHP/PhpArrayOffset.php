<?php
namespace Skrz\Meta\PHP;

use Doctrine\Common\Annotations\Annotation;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 *
 * @Annotation
 */
final class PhpArrayOffset
{

	const DEFAULT_GROUP = null;

	/** @var string */
	public $offset;

	/** @var string */
	public $group = PhpArrayOffset::DEFAULT_GROUP;

	/** @var boolean */
	public $ignoreNull = false;

}
