<?php
	namespace tradeSystem\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MACDHistogrammReverseStrategyTestCase extends TradeSystemTestCase
	{
		private $savedPortfolio = null;
		private $savedPositionStorage = null;

		public function setUp()
		{
			$this->saveSingleton(\tradeSystem\Portfolio::me());
			$this->saveSingleton(\tradeSystem\PositionStorage::me());
		}

		public function tearDown()
		{
			$this->restoreSingleton(\tradeSystem\Portfolio::me());
			$this->restoreSingleton(\tradeSystem\PositionStorage::me());
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
				addIndicator($shortEMA)->
				addIndicator($longEMA)->
				addIndicator($MACD)->
				addIndicator($MACDSignal)->
				addIndicator($MACDHistogramm);

			$strategy =
				new \tradeSystem\MACDHistogrammReverseStrategy(
					new \tradeSystem\EndStrategy()
				);

			$strategy->
				setIndicator($MACDHistogramm)->
				setSecurity(\tradeSystem\Security::create()->setId('SBER3'));

			$strategy = new BeginStrategy($strategy);

			\tradeSystem\Portfolio::me()->setBalance(10000);

			$barReader =
				\tradeSystem\FinamBarReader::create()->
				setFileName(CASES_DIR."/input/SBER_110601_110901.txt")->
				skipHead();

			$assertBalance = array(
				'20110608 120000' => 10000,
				'20110608 130000' => 83.6, // count: 104, short 95.35, SL: 96.3035, TP: 93.443, TPI: 0.4

				// LOSE SL, high is 96.48,
				// count: 103, long 96.04, SL: 95.0796, TP: 97.9608, TPI: 0.4
				'20110609 100000' => 8.716,
				'20110609 170000' => 10066.666, // WIN TP: high 98.05, sell: 97.65
				'20110610 170000' => 8.716, // count: 103, short 97.65, SL: 98.6265, TP: 95.697, TPI: 0.4,
				'20110615 150000' => 9966.0865 // LOSE SL, high is 98.78
			);

			while(
				($bar = $barReader->getNext())
				&& $barReader->getRow() < 100
			) {
				$chart->handleBar($bar);
				$strategy->handleBar($bar);

				if (isset($assertBalance[$bar->getDateTime()])) {
					$this->assertEquals(
						$assertBalance[$bar->getDateTime()],
						\tradeSystem\Portfolio::me()->getBalance()
					);
				}
			}
		}
	}
?>