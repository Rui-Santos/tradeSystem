<?php
	namespace tradeSystem\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseIndicatorTest extends TradeSystemTestCase
	{
		private $series = array(
			null, 102.4, 101.66, 101.24, 101.75, 101.32, 101.40,
			102.70, 102.30, 102.10, 102.46, 101.85
		);

		private $bigSeries = array(
			68.06000, 68.85000, 69.03000, 67.90000, 68.28000, 69.12000, 69.05000,
			70.25000, 69.57000, 70.50000,
			70.42000, 69.40000, 69.70000, 70.65000, 71.21000, 71.38000, 70.91000,
			71.01000, 70.80000, 71.15000, 70.75000, 70.67000, 70.20000, 70.10000,
			70.70000, 70.36000, 70.49000, 70.23000, 70.27000, 70.35000, 70.56000,
			71.00000, 69.63000, 69.31000, 68.37000, 68.44000, 68.58000, 68.69000,
			68.75000, 69.34000, 70.70000, 70.07000, 69.87000, 69.36000, 69.99000,

		);

		public function getSeries($offset, $length)
		{
			if (($offset+$length) > count($this->series))
				throw new \Exception('sorry, I can\'t give you such big series');

			return array_slice($this->series, $offset, $length);
		}

		public function getBigSeries($offset, $length)
		{
			if (($offset+$length) > count($this->bigSeries))
				throw new \Exception('sorry, I can\'t give you such big series');

			return array_slice($this->bigSeries, $offset, $length);
		}
	}
?>