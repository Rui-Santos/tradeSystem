<?php
	namespace tradeSystem\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class SeriesCounterTestCase extends TradeSystemTestCase
	{
		public function testCommon()
		{
			$counter = \tradeSystem\SeriesCounter::create();

			$counter->win();
			$counter->win();
			$counter->win();
			$counter->lose();
			$counter->lose();
			$counter->win();
			$counter->lose();
			$counter->lose();
			$counter->lose();
			$counter->lose();

			$this->assertSame(3, $counter->getWinSeries());
			$this->assertSame(4, $counter->getLoseSeries());
		}
	}
?>