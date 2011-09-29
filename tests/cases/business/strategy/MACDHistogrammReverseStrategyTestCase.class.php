<?php
	namespace tradeSystem\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MACDHistogrammReverseStrategyTestCase extends TradeSystemTestCase
	{
		public function setUp()
		{
			$this->saveSingleton(\tradeSystem\Portfolio::me());
			$this->saveSingleton(\tradeSystem\Log::me());
			$this->saveSingleton(\tradeSystem\PositionStorage::me());
			$this->saveSingleton(\tradeSystem\DateTimeManager::me());
		}

		public function tearDown()
		{
			$this->restoreSingleton(\tradeSystem\Portfolio::me());
			$this->restoreSingleton(\tradeSystem\Log::me());
			$this->restoreSingleton(\tradeSystem\PositionStorage::me());
			$this->restoreSingleton(\tradeSystem\DateTimeManager::me());
		}

		public function testCommon()
		{
			$shortEMA = \tradeSystem\EMA::create(12);
			$longEMA = \tradeSystem\EMA::create(26);

			$MACD = \tradeSystem\MACD::create($shortEMA, $longEMA);

			$MACDSignal = \tradeSystem\MACDSignal::create($MACD);

			$MACDHistogramm =
				\tradeSystem\MACDHistogramm::create($MACD, $MACDSignal);

			$chart =
				\tradeSystem\Chart::create()->
				setInterval(\tradeSystem\ChartInterval::hourly())->
				addIndicator($shortEMA)->
				addIndicator($longEMA)->
				addIndicator($MACD)->
				addIndicator($MACDSignal)->
				addIndicator($MACDHistogramm);

			$seriesCounter = \tradeSystem\SeriesCounter::create();

			$strategy =
				new \tradeSystem\MACDHistogrammReverseStrategy(
					new \tradeSystem\EndStrategy()
				);

			$strategy->
				setSeriesCounter($seriesCounter)->
				setIndicator($MACDHistogramm)->
				setSecurity(\tradeSystem\Security::create()->setId('SBER3'));

			$strategy = new \tradeSystem\BeginStrategy($strategy);

			\tradeSystem\Portfolio::me()->setBalance(10000);

			$barReader =
				\tradeSystem\FinamBarReader::create()->
				setFileName(CASES_DIR."/input/SBER_110601_110901.txt")->
				skipHead();

			$assertBalance = array(
				'2011-06-08 12:00:00' => 10000,
				'2011-06-08 13:00:00' => 83.6, // count: 104, short 95.35, SL: 96.3035, TP: 93.443, TPI: 0.4

				// LOSE SL, high is 96.48,
				// count: 103, long 96.04, SL: 95.0796, TP: 97.9608, TPI: 0.4
				'2011-06-09 10:00:00' => 8.716,
				'2011-06-09 17:00:00' => 10066.666, // WIN TP: high 98.05, sell: 97.65
				'2011-06-10 17:00:00' => 8.716, // count: 103, short 97.65, SL: 98.6265, TP: 95.697, TPI: 0.4,
				'2011-06-15 15:00:00' => 9966.0865 // LOSE SL, high is 98.78
			);

			while(
				($bar = $barReader->getNext())
				&& $barReader->getRow() < 100
			) {
				$dateTime = $bar->getDateTime()->format('Y-m-d H:i:s');

				\tradeSystem\DateTimeManager::me()->setNow(
					\ewgraFramework\DateTime::create(
						$bar->getDateTime()->format('Y-m-d H:59:59')
					)
				);

				$chart->handleBar($bar);
				$strategy->handleBar($bar);

				if (isset($assertBalance[$dateTime])) {
					$this->assertEquals(
						$assertBalance[$dateTime],
						\tradeSystem\Portfolio::me()->getBalance()
					);
				}
			}

			$this->assertSame(1, $seriesCounter->getWinSeries());
			$this->assertSame(1, $seriesCounter->getLoseSeries());
		}
	}
?>