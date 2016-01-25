<?php
namespace Skrz\Meta\Fixtures\PHP;

use Skrz\Meta\AbstractMetaSpec;
use Skrz\Meta\DateTimeFormattingSerializer;
use Skrz\Meta\PHP\PhpModule;

class PhpMetaSpec extends AbstractMetaSpec
{

	protected function configure()
	{
		$this->match("Skrz\\Meta\\Fixtures\\PHP\\ClassWith*")
			->addModule($phpModule = new PhpModule());

		$phpModule->addPropertySerializer(new DateTimeFormattingSerializer("Y-m-d H:i:s"));
	}

}
