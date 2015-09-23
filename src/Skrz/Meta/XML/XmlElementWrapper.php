<?php
namespace Skrz\Meta\XML;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 *
 * @Annotation
 */
final class XmlElementWrapper implements XmlAnnotationInterface
{

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
