<?php
namespace Skrz\Meta\Fixtures\XML;

use Skrz\Meta\XML\XmlAttribute;
use Skrz\Meta\XML\XmlElement;
use Skrz\Meta\XML\XmlValue;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 *
 * @XmlElement(name="paramroot")
 */
class Param
{

	/**
	 * @var string
	 *
	 * @XmlAttribute(name="name")
	 */
	public $name;

	/**
	 * @var string
	 *
	 * @XmlValue
	 */
	public $value;

}
