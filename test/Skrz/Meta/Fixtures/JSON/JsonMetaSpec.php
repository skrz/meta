<?php
namespace Skrz\Meta\Fixtures\JSON;

use Skrz\Meta\AbstractMetaSpec;
use Skrz\Meta\JSON\JsonModule;
use Skrz\Meta\PHP\PhpModule;

class JsonMetaSpec extends AbstractMetaSpec
{

	protected function configure()
	{
		$this->match("Skrz\\Meta\\Fixtures\\JSON\\ClassWith*")
			->addModule(new PhpModule())
			->addModule(new JsonModule());
	}

}
