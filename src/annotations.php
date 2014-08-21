<?php
use Doctrine\Common\Annotations\AnnotationRegistry;

foreach (get_declared_classes() as $className) {
	if (preg_match("/^ComposerAutoloaderInit/", $className)) {
		AnnotationRegistry::registerLoader(array($className::getLoader(), "loadClass"));
	}
}
