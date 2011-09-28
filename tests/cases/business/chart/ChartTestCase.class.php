<?php
	namespace tradeSystem\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ChartTestCase extends TradeSystemTestCase
	{
		public function testBarDiffInterval()
		{
			$chart =
				\tradeSystem\Chart::create()->
				setInterval(\tradeSystem\ChartInterval::hourly());

			$bar =
				\tradeSystem\Bar::create()->
				setOpen(100)->
				setClose(101)->
				setLow(99)->
				setHigh(102)->
				setDateTime(
					\ewgraFramework\DateTime::create('20110101 100000')
				);

			$chart->handleBar($bar);

			$this->assertEquals($bar, $chart->getBarFromEnd());

			$bar =
				\tradeSystem\Bar::create()->
				setOpen(100.1)->
				setClose(101.1)->
				setLow(98)->
				setHigh(101.5)->
				setDateTime(
					\ewgraFramework\DateTime::create('20110101 100001')
				);

			$chart->handleBar($bar);

			$assertBar =
				\tradeSystem\Bar::create()->
				setOpen(100)->
				setClose(101.1)->
				setLow(98)->
				setHigh(102)->
				setDateTime(
					\ewgraFramework\DateTime::create('20110101 100000')
				);

			$this->assertEquals($assertBar, $chart->getBarFromEnd());

			$bar =
				\tradeSystem\Bar::create()->
				setOpen(200.1)->
				setClose(201.1)->
				setLow(198)->
				setHigh(201.5)->
				setDateTime(
					\ewgraFramework\DateTime::create('20110101 110001')
				);

			$chart->handleBar($bar);

			$assertBar =
				\tradeSystem\Bar::create()->
				setOpen(200.1)->
				setClose(201.1)->
				setLow(198)->
				setHigh(201.5)->
				setDateTime(
					\ewgraFramework\DateTime::create('20110101 110000')
				);

			$this->assertEquals($assertBar, $chart->getBarFromEnd());
		}
	}
?>