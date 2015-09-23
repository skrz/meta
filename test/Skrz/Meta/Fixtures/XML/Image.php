<?php
namespace Skrz\Meta\Fixtures\XML;

use Skrz\Meta\XML\XmlElement;
use Skrz\Meta\XML\XmlValue;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 *
 * @XmlElement(name="imageroot")
 */
class Image
{

	/**
	 * @var string
	 *
	 * @XmlValue
	 */
	public $url;

}
