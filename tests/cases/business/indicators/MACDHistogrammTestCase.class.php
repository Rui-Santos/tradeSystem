<?php
	namespace tradeSystem\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MACDHistogrammTestCase extends BaseIndicatorTest
	{
		public function testCommon()
		{
			$resultSeries = array_fill(0, 33, null);
			$resultSeries[] = -0.19268;
			$resultSeries[] = -0.27893;
			$resultSeries[] = -0.31616;
			$resultSeries[] = -0.31553;
			$resultSeries[] = -0.2924;
			$resultSeries[] = -0.25889;
			$resultSeries[] = -0.18624;
			$resultSeries[] = -0.04328;
			$resultSeries[] = 0.00936;
			$resultSeries[] = 0.03004;
			$resultSeries[] = 0.00976;
			$resultSeries[] = 0.03758;

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

			foreach ($this->getBigSeries(0, 45) as $key => $value) {
				$chart->handleBar(
					\tradeSystem\Bar::create()->
					setClose($value)
				);

				$this->assertSame($resultSeries[$key], $MACDHistogramm->getValue());
			}
		}
	}
?>