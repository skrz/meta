#!/usr/bin/env php
<?php
namespace Skrz\Meta;

foreach (array(getcwd() . "/vendor/autoload.php", __DIR__ . "/../vendor/autoload.php") as $autoloadFile) {
	if (file_exists($autoloadFile)) {
		require_once $autoloadFile;
		break;
	}
}

use Symfony\Component\Console\Application;

$app = new Application("autowiring");
$app->add(new MetaCommand());
$app->run();
