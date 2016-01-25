<?php
namespace Skrz\Meta\XML;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 *
 * @Annotation
 */
final class XmlElement implements XmlAnnotationInterface
{

	const DEFAULT_GROUP = null;

	/** @var string */
	public $namespace = "";

	/** @var string */
	public $name;

	/** @var string */
	public $group = XmlElement::DEFAULT_GROUP;

	/**
	 * @return string
	 */
	public function getGroup()
	{
		return $this->group;
	}

}
