<?php
namespace Skrz\Meta;

use Skrz\Meta\Fixtures\XML\Image;
use Skrz\Meta\Fixtures\XML\Meta\ImageMeta;
use Skrz\Meta\Fixtures\XML\Meta\ParamMeta;
use Skrz\Meta\Fixtures\XML\Meta\ProductMeta;
use Skrz\Meta\Fixtures\XML\Param;
use Skrz\Meta\Fixtures\XML\Product;
use Skrz\Meta\Fixtures\XML\XmlMetaSpec;
use Skrz\Meta\XML\XmlMetaInterface;
use Symfony\Component\Finder\Finder;

class XmlModuleTest extends \PHPUnit_Framework_TestCase
{

	public static function setUpBeforeClass()
	{
		$files = array_map(function (\SplFileInfo $file) {
			return $file->getPathname();
		}, iterator_to_array(
			(new Finder())
				->in(__DIR__ . "/Fixtures/XML")
				->name("*.php")
				->notName("*Meta*")
				->files()
		));

		$spec = new XmlMetaSpec();
		$spec->processFiles($files);
	}

	public function fromXmlDataProvider()
	{
		return [
			[
				function () {
					$doc = new \DOMDocument();
					$el = $doc->createElement("imgurl", "http://example.net/image.jpg");
					$doc->appendChild($el);

					return $doc;
				},
				function () {
					return ImageMeta::getInstance();
				},
				function (Image $image) {
					$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\XML\\Image", $image);
					$this->assertEquals("http://example.net/image.jpg", $image->url);
				},
			],
			[
				function () {
					$doc = new \DOMDocument();
					$el = $doc->createElement("param");
					$el->setAttribute("name", "color");
					$el->appendChild(new \DOMText("red"));
					$doc->appendChild($el);

					return $doc;
				},
				function () {
					return ParamMeta::getInstance();
				},
				function (Param $param) {
					$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\XML\\Param", $param);
					$this->assertEquals("color", $param->name);
					$this->assertEquals("red", $param->value);
				},
			],
			[
				function () {
					$doc = new \DOMDocument();
					$productEl = $doc->createElement("product");
					$productEl->setAttribute("uid", 1);
					$productEl->appendChild($doc->createElement("item_id", "SKU123"));

					$ignoredEl = $doc->createElement("ignored");
					$ignoredEl->appendChild($doc->createElement("item_id", "IGNSKU"));
					$ignoredEl->appendChild($doc->createElement("category", "Ignored category"));
					$productEl->appendChild($ignoredEl);

					$createdAtEl = $doc->createElement("created_at");
					$createdAtEl->appendChild($doc->createTextNode((new \DateTime("2016-10-07"))->format(\DateTime::ISO8601)));
					$productEl->appendChild($createdAtEl);

					$skrzEl = $doc->createElement("skrz");
					$skrzEl->appendChild($doc->createElement("item_id", "SKRZSKU"));
					$skrzEl->appendChild($doc->createElement("priceorig", "1000"));
					$skrzEl->appendChild($doc->createElement("discount", "15"));
					$productEl->appendChild($skrzEl);

					$paramsEl = $doc->createElement("params");
					for ($i = 0; $i < 10; ++$i) {
						$paramEl = $doc->createElement("param");
						$paramEl->setAttribute("name", "param{$i}");
						$paramEl->appendChild(new \DOMText("value{$i}"));
						$paramsEl->appendChild($paramEl);
					}
					$productEl->appendChild($paramsEl);

					for ($i = 0; $i < 3; ++$i) {
						$categoryEl = $doc->createElement("category", "Category #{$i}");
						$productEl->appendChild($categoryEl);
					}

					for ($i = 0; $i < 5; ++$i) {
						$imageEl = $doc->createElement("imgurl", "http://example.net/{$i}.jpg");
						$productEl->appendChild($imageEl);
					}

					$doc->appendChild($productEl);

					return $doc;
				},
				function () {
					return ProductMeta::getInstance();
				},
				function (Product $product) {
					$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\XML\\Product", $product);
					$this->assertEquals(1, $product->uid);
					$this->assertEquals("SKU123", $product->remoteItemId);
					$this->assertEquals(1000, $product->priceOrig);
					$this->assertEquals(15, $product->discount);

					$this->assertNotEmpty($product->categories);
					$this->assertCount(3, $product->categories);
					for ($i = 0; $i < count($product->categories); ++$i) {
						$this->assertEquals("Category #{$i}", $product->categories[$i]);
					}

					$this->assertNotEmpty($product->images);
					$this->assertCount(5, $product->images);
					for ($i = 0; $i < count($product->images); ++$i) {
						$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\XML\\Image", $product->images[$i]);
						$this->assertEquals("http://example.net/{$i}.jpg", $product->images[$i]->url);
					}

					$this->assertNotEmpty($product->params);
					$this->assertCount(10, $product->params);
					for ($i = 0; $i < count($product->params); ++$i) {
						$this->assertInstanceOf("Skrz\\Meta\\Fixtures\\XML\\Param", $product->params[$i]);
						$this->assertEquals("param{$i}", $product->params[$i]->name);
						$this->assertEquals("value{$i}", $product->params[$i]->value);
					}

					$this->assertEquals((new \DateTime("2016-10-07"))->getTimestamp(), $product->createdAt->getTimestamp());
				},
			],
		];
	}

	/**
	 * @dataProvider fromXmlDataProvider
	 */
	public function testFromXmlReader(callable $buildDoc, callable $buildMeta, callable $validate)
	{
		/** @var \DOMDocument $doc */
		$doc = $buildDoc();
		/** @var XmlMetaInterface $meta */
		$meta = $buildMeta();

		$xml = new \XMLReader();
		$xml->XML($doc->saveXML());
		$xml->next();

		$instance = $meta->fromXml($xml);

		$validate($instance);
	}

	/**
	 * @dataProvider fromXmlDataProvider
	 */
	public function testFromXmlElement(callable $buildDoc, callable $buildMeta, callable $validate)
	{
		/** @var \DOMDocument $doc */
		$doc = $buildDoc();
		/** @var XmlMetaInterface $meta */
		$meta = $buildMeta();

		$instance = $meta->fromXml($doc->firstChild);

		$validate($instance);
	}

	public function toXmlDataProvider()
	{
		return [
			[
				function () {
					$image = new Image();
					$image->url = "http://example.net/image.jpg";

					return $image;
				},
				function () {
					return ImageMeta::getInstance();
				},
				'<?xml version="1.0"?>' . PHP_EOL .
				'<imageroot>http://example.net/image.jpg</imageroot>' . PHP_EOL,
			],
			[
				function () {
					$param = new Param();
					$param->name = "color";
					$param->value = "blue";

					return $param;
				},
				function () {
					return ParamMeta::getInstance();
				},
				'<?xml version="1.0"?>' . PHP_EOL .
				'<paramroot name="color">blue</paramroot>' . PHP_EOL,
			],
			[
				function () {
					$product = new Product();

					return $product;
				},
				function () {
					return ProductMeta::getInstance();
				},
				'<?xml version="1.0"?>' . PHP_EOL .
				'<productroot><params/></productroot>' . PHP_EOL,
			],
			[
				function () {
					$product = new Product();
					$product->uid = 2423;

					return $product;
				},
				function () {
					return ProductMeta::getInstance();
				},
				'<?xml version="1.0"?>' . PHP_EOL .
				'<productroot uid="2423"><params/></productroot>' . PHP_EOL,
			],
			[
				function () {
					$product = new Product();
					$product->uid = 2423;
					$product->remoteItemId = "SDFA";

					return $product;
				},
				function () {
					return ProductMeta::getInstance();
				},
				'<?xml version="1.0"?>' . PHP_EOL .
				'<productroot uid="2423"><item_id>SDFA</item_id><params/></productroot>' . PHP_EOL,
			],
			[
				function () {
					$product = new Product();
					$product->uid = 2423;
					$product->categories = array("A", "B", "C");

					return $product;
				},
				function () {
					return ProductMeta::getInstance();
				},
				'<?xml version="1.0"?>' . PHP_EOL .
				'<productroot uid="2423"><category>A</category><category>B</category><category>C</category><params/></productroot>' . PHP_EOL,
			],
			[
				function () {
					$imageA = new Image();
					$imageA->url = "http://example.net/a.jpg";
					$imageB = new Image();
					$imageB->url = "http://example.net/b.jpg";

					$product = new Product();
					$product->uid = 2423;
					$product->images = array($imageA, $imageB);

					return $product;
				},
				function () {
					return ProductMeta::getInstance();
				},
				'<?xml version="1.0"?>' . PHP_EOL .
				'<productroot uid="2423"><imgurl>http://example.net/a.jpg</imgurl><imgurl>http://example.net/b.jpg</imgurl><params/></productroot>' . PHP_EOL,
			],
			[
				function () {
					$product = new Product();
					$product->priceOrig = 1500;
					$product->discount = 30;

					return $product;
				},
				function () {
					return ProductMeta::getInstance();
				},
				'<?xml version="1.0"?>' . PHP_EOL .
				'<productroot><params/><skrz><priceorig>1500</priceorig><discount>30</discount></skrz></productroot>' . PHP_EOL,
			],
		];
	}

	/**
	 * @dataProvider toXmlDataProvider
	 */
	public function testToXmlWriter(callable $buildEntity, callable $buildMeta, $expected)
	{
		/** @var XmlMetaInterface $meta */
		$meta = $buildMeta();

		$xml = new \XMLWriter();
		$xml->openMemory();
		$xml->startDocument();

		$meta->toXml($buildEntity(), null, $xml);

		$xml->endDocument();

		$this->assertEquals($expected, $xml->outputMemory());
		$this->assertCount(0, Stack::$objects);

		// works with null filter
		$xml = new \XMLWriter();
		$xml->openMemory();
		$xml->startDocument();

		$meta->toXml($buildEntity(), null, null, $xml);

		$xml->endDocument();

		$this->assertEquals($expected, $xml->outputMemory());
		$this->assertCount(0, Stack::$objects);
	}

	/**
	 * @dataProvider toXmlDataProvider
	 */
	public function testToXmlElement(callable $buildEntity, callable $buildMeta, $expected)
	{
		/** @var XmlMetaInterface $meta */
		$meta = $buildMeta();

		$xml = new \DOMDocument();

		$xml->appendChild($meta->toXml($buildEntity(), null, $xml));

		$this->assertEquals($expected, $xml->saveXML());
		$this->assertCount(0, Stack::$objects);
	}

}
