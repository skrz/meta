<?php
namespace Skrz\Meta;

use Skrz\Meta\Reflection\Type;

class MetaSpecMatcher
{

	/** @var AbstractMetaSpec */
	private $spec;

	private $includePatternRegexps = array();

	private $excludePatternRegexps = array();

	/** @var AbstractModule[] */
	private $modules;

	/**
	 * @param AbstractMetaSpec $spec
	 */
	public function __construct(AbstractMetaSpec $spec)
	{
		$this->spec = $spec;
		$this->modules = array(new BaseModule());
	}

	/**
	 * @param string $pattern
	 * @return $this
	 */
	public function match($pattern)
	{
		$this->includePatternRegexps[] = $this->compilePattern($pattern);
		return $this;
	}

	/**
	 * @param string $pattern
	 * @return $this
	 */
	public function notMatch($pattern)
	{
		$this->excludePatternRegexps[] = $this->compilePattern($pattern);
		return $this;
	}

	/**
	 * @param Type $type
	 * @return boolean
	 */
	public function matches(Type $type)
	{
		$typeName = $type->getName();

		foreach ($this->excludePatternRegexps as $excludePatternRegexp) {
			if (preg_match($excludePatternRegexp, $typeName)) {
				return false;
			}
		}

		foreach ($this->includePatternRegexps as $includePatternRegexp) {
			if (preg_match($includePatternRegexp, $typeName)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return AbstractModule[]
	 */
	public function getModules()
	{
		return $this->modules;
	}

	/**
	 * @param AbstractModule $module
	 * @return $this
	 */
	public function addModule(AbstractModule $module)
	{
		$this->modules[] = $module;
		$module->onAdd($this->spec, $this);
		return $this;
	}

	/**
	 * @param string $pattern
	 * @return string
	 */
	private function compilePattern($pattern)
	{
		return "/^" . str_replace(
			array("\\", "**", "*"),
			array(preg_quote("\\"), ".+", "[^\\\\]+"),
			$pattern
		) . "$/";
	}

}
