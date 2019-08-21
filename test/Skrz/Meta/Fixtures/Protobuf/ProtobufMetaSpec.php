<?php
namespace Skrz\Meta\Fixtures\Protobuf;

use Skrz\Meta\AbstractMetaSpec;
use Skrz\Meta\PHP\PhpModule;
use Skrz\Meta\Protobuf\ProtobufModule;

class ProtobufMetaSpec extends AbstractMetaSpec
{

	protected function configure()
	{
		$this
			->match("Skrz\\Meta\\Fixtures\\Protobuf\\ClassWith**")
			->match("Skrz\\Meta\\Fixtures\\Protobuf\\ClassWithEmbeddedMessageProperty\\Embedded")
			->notMatch("**Enum")
			->addModule(new PhpModule())
			->addModule(new ProtobufModule());
	}

}
