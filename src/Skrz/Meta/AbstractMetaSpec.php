<?php
namespace Skrz\Meta;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Skrz\Meta\Reflection\Type;
use Symfony\Component\Finder\Finder;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 *
 * @deprecated Use MetaSpec instead.
 */
abstract class AbstractMetaSpec
{

	/** @var string */
	private $outputPath;

	/** @var MetaSpecMatcher[] */
	private $matchers = array();

	public function __construct()
	{
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
	public function match($pattern)
	{
		$this->matchers[] = $matcher = new MetaSpecMatcher($this);
		return $matcher->match($pattern);
	}

	/**
	 * @param Finder $finder
	 * @return void
	 */
	public function prepareFinder(Finder $finder)
	{

	}

	/**
	 * @param string $fileName
	 * @return boolean
	 */
	public function processFile($fileName)
	{
		return $this->processFiles((array)$fileName);
	}

	/**
	 * @param string[] $fileNames
	 * @throws MetaException
	 * @return boolean
	 */
	public function processFiles(array $fileNames)
	{
		$types = array();
		foreach ($fileNames as $fileName) {
			require_once $fileName;
		}

		foreach (array_merge(get_declared_classes(), get_declared_interfaces(), get_declared_traits()) as $typeName) {
			$rc = new \ReflectionClass($typeName);
			if ($rc->getFileName() && in_array(realpath($rc->getFileName()), $fileNames)) {
				$types[] = Type::fromReflection($rc);
			}
		}

		$matched = false;

		foreach ($types as $type) {
			$result = $this->compile($type);

			if ($result === null) {
				continue;
			}

			$matched = true;

			$outputFileName = $this->createOutputFileName($type, $result->getClass());
			$outputDirectory = dirname($outputFileName);

			if (!is_dir($outputDirectory)) {
				if (!mkdir($outputDirectory, 0777, true)) {
					throw new MetaException("Could not create output directory '{$outputDirectory}'.");
				}
			}

			$content = (string)$result->getFile();

			// do not overwrite files with same content
			if (!file_exists($outputFileName) || md5_file($outputFileName) !== md5($content)) {
				if (!file_put_contents($outputFileName, $content)) {
					throw new MetaException("Could not write output to file '{$outputFileName}'.");
				}
			}
		}

		return $matched;
	}

	/**
	 * @param Type $type
	 * @return Result
	 */
	public function compile(Type $type)
	{
		if ($type->isAbstract()) {
			return null;
		}

		foreach ($this->matchers as $matcher) {
			if (!$matcher->matches($type)) {
				continue;
			}

			foreach ($matcher->getModules() as $module) {
				$module->onBeforeGenerate($this, $matcher, $type);
			}

			$file = new PhpFile();
			$class = $this->createMetaClass($type, $file);

			foreach ($matcher->getModules() as $module) {
				$module->onGenerate($this, $matcher, $type, $class);
			}

			return new Result($file, $class);
		}

		return null;
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
