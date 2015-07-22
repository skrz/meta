<?php
namespace Skrz\Meta\PHP;

class StatementAndExpressionVO
{

	/** @var string */
	private $statement;

	/** @var string */
	private $expression;

	/**
	 * @return StatementAndExpressionVO
	 */
	public static function create()
	{
		return new StatementAndExpressionVO();
	}

	/**
	 * @param $expression
	 * @return StatementAndExpressionVO
	 */
	public static function withExpression($expression)
	{
		return StatementAndExpressionVO::create()
			->setExpression($expression);
	}

	/**
	 * @param string $statement
	 * @param string $expression
	 * @return StatementAndExpressionVO
	 */
	public static function withStatementAndExpression($statement, $expression)
	{
		return StatementAndExpressionVO::create()
			->setStatement($statement)
			->setExpression($expression);
	}

	/**
	 * @param string $expression
	 * @return $this
	 */
	public function setExpression($expression)
	{
		$this->expression = $expression;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getExpression()
	{
		return $this->expression;
	}

	/**
	 * @param string $statement
	 * @return $this
	 */
	public function setStatement($statement)
	{
		$this->statement = $statement;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getStatement()
	{
		return $this->statement;
	}

}
