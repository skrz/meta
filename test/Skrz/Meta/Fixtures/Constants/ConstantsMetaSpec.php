<?php
namespace Skrz\Meta\Fixtures\Constants;

use Skrz\Meta\AbstractMetaSpec;
use Skrz\Meta\ConstantsModule;

class ConstantsMetaSpec extends AbstractMetaSpec
{

	protected function configure()
	{
		$this
			->match("Skrz\\Meta\\Fixtures\\Constants\\Class*")
			->addModule(new ConstantsModule());
	}

}
