<?php
namespace Skrz\Meta\Fixtures\PHP;

use Skrz\Meta\AbstractMetaSpec;
use Skrz\Meta\PHP\PhpModule;

class PhpMetaSpec extends AbstractMetaSpec
{

	protected function configure()
	{
		$this->match("Skrz\\Meta\\Fixtures\\PHP\\ClassWith*")
			->addModule(new PhpModule());
	}

}
 