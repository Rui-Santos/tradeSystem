<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Math
	{
		const PRECISION = 5;

		public static function summArray(array $arrayValues)
		{
			$result = 0;

			foreach ($arrayValues as $value)
				$result = bcadd($result, $value, self::PRECISION);

			return self::formatFloat($result);
		}

		public static function abs($one)
		{
			return
				self::gt($one, 0)
					? $one
					: self::multiply($one, -1);
		}

		public static function div($one, $two, $format = true)
		{
			$result = bcdiv($one, $two, self::PRECISION);

			return
				$format
					? self::formatFloat($result)
					: $result;
		}

		public static function compare($one, $two)
		{
			return bccomp($one, $two, self::PRECISION);
		}

		public static function add($one, $two)
		{
			return self::formatFloat(bcadd($one, $two, self::PRECISION));
		}

		public static function sub($one, $two)
		{
			return self::formatFloat(bcsub($one, $two, self::PRECISION));
		}

		public static function multiply($one, $two, $format = true)
		{
			$result = bcmul($one, $two, self::PRECISION);

			return
				$format
					? self::formatFloat($result)
					: $result;
		}

		public static function eq($one, $two)
		{
			return self::compare($one, $two) === 0;
		}

		public static function notEq($one, $two)
		{
			return !self::eq($one, $two);
		}

		public static function gt($one, $two)
		{
			return self::compare($one, $two) === 1;
		}

		public static function lt($one, $two)
		{
			return self::compare($one, $two) === -1;
		}

		public static function eqGt($one, $two)
		{
			return (
				self::eq($one, $two)
				|| self::gt($one, $two)
			);
		}

		public static function eqLt($one, $two)
		{
			return (
				self::eq($one, $two)
				|| self::lt($one, $two)
			);
		}

		private static function formatFloat($var)
		{
			return (float)$var;
		}
	}
?>