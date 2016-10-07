<?php
namespace Skrz\Meta\Fixtures\XML;

use Skrz\Meta\XML\XmlAttribute;
use Skrz\Meta\XML\XmlElement;
use Skrz\Meta\XML\XmlElementWrapper;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 *
 * @XmlElement(name="productroot")
 */
class Product
{

	/**
	 * @var int
	 *
	 * @XmlAttribute(name="uid")
	 */
	public $uid;

	/**
	 * @var string
	 *
	 * @XmlElement(name="item_id")
	 */
	public $remoteItemId;

	/**
	 * @var string[]
	 *
	 * @XmlElement(name="category")
	 */
	public $categories = [];

	/**
	 * @var Image[]
	 *
	 * @XmlElement(name="imgurl")
	 */
	public $images = [];

	/**
	 * @var Param[]
	 *
	 * @XmlElementWrapper(name="params")
	 * @XmlElement(name="param")
	 */
	public $params = [];

	/**
	 * @var float
	 *
	 * @XmlElementWrapper(name="skrz")
	 * @XmlElement(name="priceorig")
	 */
	public $priceOrig;

	/**
	 * @var int
	 *
	 * @XmlElementWrapper(name="skrz")
	 * @XmlElement(name="discount")
	 */
	public $discount;

	/**
	 * @var \DateTime
	 *
	 * @XmlElement(name="created_at")
	 */
	public $createdAt;

}
