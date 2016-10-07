<?php
namespace Skrz\Meta\Fixtures\XML;

use Skrz\Meta\AbstractMetaSpec;
use Skrz\Meta\DateTimeFormattingSerializer;
use Skrz\Meta\XML\XmlModule;

class XmlMetaSpec extends AbstractMetaSpec
{

	protected function configure()
	{
		$this->match("Skrz\\Meta\\Fixtures\\XML\\*")
			->addModule($xmlModule = new XmlModule());

		$xmlModule->addPropertySerializer(new DateTimeFormattingSerializer(\DateTime::ISO8601));
	}

}
