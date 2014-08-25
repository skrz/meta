<?php
namespace Skrz\Meta;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Skrz\Meta\Reflection\Reflector;
use Skrz\Meta\Reflection\Type;

abstract class AbstractMetaSpec
{

	/** @var string */
	private $outputPath;

	/** @var Reflector */
	private $reflector;

	/** @var MetaSpecMatcher[] */
	private $matchers = array();

	public function __construct()
	{
		$this->reflector = new Reflector();
		$this->configure();
	}

	/**
	 * @return void
	 */
	abstract protected function configure();

	/**
	 * @param string $outputPath
	 * @return $this
	 */
	public function setOutputPath($outputPath)
	{
		$this->outputPath = $outputPath;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getOutputPath()
	{
		return $this->outputPath;
	}

	/**
	 * @param string $pattern
	 * @return MetaSpecMatcher
	 */
	protected function match($pattern)
	{
		$this->matchers[] = $matcher = new MetaSpecMatcher($this);
		return $matcher->match($pattern);
	}

	/**
	 * @param string[] $fileNames
	 * @throws MetaException
	 */
	public function processFiles(array $fileNames)
	{
		$types = array();
		foreach ($fileNames as $fileName) {
			$types = array_merge($types, $this->reflector->reflectFile($fileName));
		}

		foreach ($types as $type) {
			foreach ($this->matchers as $matcher) {
				if ($matcher->matches($type)) {
					foreach ($matcher->getModules() as $module) {
						$module->onBeforeGenerate($this, $matcher, $type);
					}

					$file = new PhpFile();
					$file
						->addDocument("!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!")
						->addDocument("!!!                                                    !!!")
						->addDocument("!!!   THIS FILE HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!")
						->addDocument("!!!                                                    !!!")
						->addDocument("!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");

					$class = $this->createMetaClass($type, $file);

					foreach ($matcher->getModules() as $module) {
						$module->onGenerate($this, $matcher, $type, $class);
					}

					$outputFileName = $this->createOutputFileName($type, $class);
					$outputDirectory = dirname($outputFileName);

					if (!is_dir($outputDirectory)) {
						if (!mkdir($outputDirectory, 0777, true)) {
							throw new MetaException("Could not create output directory '{$outputDirectory}'.");
						}
					}

					if (!file_put_contents($outputFileName, (string)$file)) {
						throw new MetaException("Could not write output to file '{$outputFileName}'.");
					}

					break;
				}
			}
		}
	}

	public function createMetaClassName(Type $type)
	{
		return $type->getNamespaceName() . "\\Meta\\" . $type->getShortName() . "Meta";
	}

	public function createMetaClass(Type $type, PhpFile $file)
	{
		return $file->addClass($this->createMetaClassName($type));
	}

	public function createOutputFileName(Type $type, ClassType $class)
	{
		if ($this->outputPath !== null) {
			return
				rtrim($this->outputPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR .
				str_replace("\\", DIRECTORY_SEPARATOR, $class->getNamespace()->getName()) . DIRECTORY_SEPARATOR .
				$class->getName() . ".php";

		} else {
			return
				dirname($type->getFileName()) . DIRECTORY_SEPARATOR .
				"Meta" . DIRECTORY_SEPARATOR .
				$class->getName() . ".php";
		}
	}

}
