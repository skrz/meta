<?php
namespace Skrz\Meta\Fixtures\Base;

use Skrz\Meta\AbstractMetaSpec;

class BaseMetaSpec extends AbstractMetaSpec
{

	protected function configure()
	{
		$this
			->match("Skrz\\Meta\\Fixtures\\Base\\Class*")
			->notMatch("**Ignored");
	}

}
