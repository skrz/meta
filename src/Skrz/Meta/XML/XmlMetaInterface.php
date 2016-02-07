<?php
namespace Skrz\Meta\XML;

use Skrz\Meta\Fields\FieldsInterface;
use Skrz\Meta\MetaInterface;

interface XmlMetaInterface extends MetaInterface
{

	/**
	 * Creates object from XML
	 *
	 * @param \XMLReader|\DOMNode $xml
	 * @param string $group
	 * @param object $object
	 *
	 * @return object
	 */
	public static function fromXml($xml, $group = XmlElement::DEFAULT_GROUP, $object = null);

	/**
	 * Serializes object into XML
	 *
	 * @param object $object
	 * @param string $group
	 * @param array|FieldsInterface|\XMLWriter|\DOMDocument $filterOrXml
	 * @param \XMLWriter|\DOMDocument|\DOMElement $xml
	 * @param \DOMElement $el
	 *
	 * @return \DOMElement|void
	 */
	public static function toXml($object, $group = XmlElement::DEFAULT_GROUP, $filterOrXml, $xml = null, $el = null);

}
