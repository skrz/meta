<?php
namespace Skrz\Meta;

use Skrz\Meta\Reflection\Type;

class MetaSpecMatcher
{

	/** @var AbstractMetaSpec */
	private $spec;

	/** @var string */
	private $pattern;

	/** @var string */
	private $patternRegex;

	/** @var AbstractModule[] */
	private $modules;

	/**
	 * @param AbstractMetaSpec $spec
	 * @param string $pattern
	 */
	public function __construct(AbstractMetaSpec $spec, $pattern)
	{
		$this->spec = $spec;
		$this->pattern = $pattern;
		$this->patternRegex = "/^" . str_replace(
			array("\\", "**", "*"),
			array(preg_quote("\\"), ".+", "[^\\\\]+"),
			$this->pattern
		) . "$/";

		$this->modules = array(new BaseModule());
	}

	/**
	 * @param Type $type
	 * @return boolean
	 */
	public function matches(Type $type)
	{
		return !!preg_match($this->patternRegex, $type->getName());
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

}
