<?php
namespace Skrz\Meta\Protobuf;

use Skrz\Meta\AbstractMetaSpec;

class ProtobufBootstrapMetaSpec extends AbstractMetaSpec
{

	protected function configure()
	{
		$this->setOutputPath(__DIR__ . "/../../../../gen-src");
		$this
			->match("Google\\**")
			->notMatch("**Enum")
			->notMatch("**Meta")
			->addModule(new ProtobufModule());
	}

}
