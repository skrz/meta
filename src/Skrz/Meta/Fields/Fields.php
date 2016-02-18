<?php
namespace Skrz\Meta\Fields;

/**
 * Fields parser - {@see fromString} - and converter - {@see fromArray}.
 *
 * @author Jakub Kulhan <jakub.kulhan@gmail.com>
 */
class Fields
{

	/**
	 * Create empty {@link FieldsBuilderInterface} instance.
	 *
	 * @return FieldsBuilderInterface
	 */
	public static function builder()
	{
		return ScalarFields::getInstance();
	}

	/**
	 * Convert given array into fields.
	 *
	 * @param array $input
	 * @return FieldsBuilderInterface
	 */
	public static function fromArray($input = [])
	{
		$fields = ScalarFields::getInstance();

		foreach ($input as $k => $v) {
			if (is_array($v)) {
				$fields = $fields->appendFields($k, self::fromArray($v));
			} elseif (!!$v) {
				$fields = $fields->appendField($k);
			}
		}

		return $fields;
	}

	/**
	 * Parse string into fields.
	 *
	 * @param $input
	 * @param int $start
	 * @param null $end
	 * @return FieldsBuilderInterface
	 */
	public static function fromString($input, $start = 0, $end = null)
	{
		return self::appendFromString(self::builder(), $input, $start, $end);
	}

	/**
	 * Add fields from given string to existing field set.
	 *
	 * @param FieldsBuilderInterface $builder
	 * @param string $input
	 * @param int $start
	 * @param int $end
	 * @return FieldsBuilderInterface
	 */
	public static function appendFromString(FieldsBuilderInterface $builder, $input, $start = 0, $end = null)
	{
		if ($end === null) {
			$end = strlen($input);
		}

		while ($start < $end) {
			$start = self::ltrim($input, $start, $end);

			if ($start >= $end) {
				return $builder;
			}

			$p = self::pos($input, $start, $end, ".{,");

			if ($p === $end) {
				$p = self::rtrim($input, $start, $p);
				$builder = $builder->appendField(substr($input, $start, $p - $start));

				$start = $end;

			} else {
				$fieldName = substr($input, $start, self::rtrim($input, $start, $p) - $start);

				switch ($input[$p]) {
					case ".":
						for ($q = self::pos($input, $p, $end, "{,"); $q < $end && $input[$q] === "{"; ++$q) {
							$q = self::findBalancedRightBrace($input, $q, $end);
						}
						$builder = $builder->appendFields(
							$fieldName,
							self::appendFromString($builder->builder($fieldName), $input, $p + 1, $q)
						);

						$start = $q + 1;
						break;

					case "{":
						$balancedRightBrace = self::findBalancedRightBrace($input, $p, $end);
						if ($balancedRightBrace + 1 < $end && $input[$balancedRightBrace + 1] !== ",") {
							throw new \InvalidArgumentException(
								"Invalid fields string '{$input}' - expected ',' at position " . ($balancedRightBrace + 1) . "."
							);
						}
						$builder = $builder->appendFields(
							$fieldName,
							self::appendFromString($builder->builder($fieldName), $input, $p + 1, $balancedRightBrace)
						);
						$start = $balancedRightBrace + 2;
						break;

					case ",":
						$builder = $builder->appendField($fieldName);
						$start = $p + 1;
						break;

					default:
						throw new \LogicException("Unhandled char '{$input[$p]}'.");
				}
			}
		}

		return $builder;
	}

	private static function pos($input, $start, $end, $charsList)
	{
		while ($start < $end && strpos($charsList, $input[$start]) === false) {
			++$start;
		}
		return $start;
	}

	private static function ltrim($input, $start, $end, $charsList = " \t\n\r\0\x0B")
	{
		while ($start < $end && strpos($charsList, $input[$start]) !== false) {
			++$start;
		}
		return $start;
	}

	private static function rtrim($input, $start, $end, $charsList = " \t\n\r\0\x0B")
	{
		while ($end > $start && strpos($charsList, $input[$end - 1]) !== false) {
			--$end;
		}
		return $end;
	}

	private static function findBalancedRightBrace($input, $start, $end)
	{
		if ($input[$start] !== "{") {
			throw new \InvalidArgumentException("Invalid fields string '{$input}' - no left brace at position {$start}.");
		}

		for ($start = $start + 1, $n = 1; $start < $end; ++$start) {
			if ($input[$start] === "{") {
				++$n;
			} else if ($input[$start] === "}") {
				if (--$n < 1) {
					return $start;
				}
			}
		}

		throw new \InvalidArgumentException("Invalid fields string '{$input}' - unbalanced braces at position {$start}.");
	}

}
