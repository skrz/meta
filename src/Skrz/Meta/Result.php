<?php
namespace Skrz\Meta;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;

/**
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
class Result
{

	/** @var PhpFile */
	private $file;

	/** @var ClassType */
	private $class;

	public function __construct(PhpFile $file, ClassType $class)
	{
		$this->file = $file;
		$this->class = $class;
	}

	/**
	 * @return PhpFile
	 */
	public function getFile()
	{
		return $this->file;
	}

	/**
	 * @param PhpFile $file
	 * @return self
	 */
	public function setFile(PhpFile $file)
	{
		$this->file = $file;
		return $this;
	}

	/**
	 * @return ClassType
	 */
	public function getClass()
	{
		return $this->class;
	}

	/**
	 * @param ClassType $class
	 * @return self
	 */
	public function setClass(ClassType $class)
	{
		$this->class = $class;
		return $this;
	}

}
