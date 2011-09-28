<?php
	namespace tradeSystem\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MACDHistogrammTestCase extends TradeSystemTestCase
	{
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

			$barReader =
				\tradeSystem\FinamBarReader::create()->
				setFileName(CASES_DIR."/input/SBER_110601_110901.txt")->
				skipHead();

			$lastBar = null;

			while(
				($bar = $barReader->getNext())
				&& $barReader->getRow() < ($longEMA->getPeriod()+$MACDSignal->getEMAPeriod()-1)
			) {
				$chart->handleBar($bar);
				$lastBar = $bar;
			}

			$chart->rollbackIndicatorsLastValue();
			$chart->handleIndicatorsBar($lastBar);

			$this->assertFalse($MACDHistogramm->hasValue());

			$assertValues = array(
				0.12384, 0.19107, 0.21427, 0.23116, 0.2663
			);

			foreach ($assertValues as $assertValue) {
				$lastBar = $barReader->getNext();
				$chart->handleBar($lastBar);
				$this->assertTrue($MACDHistogramm->hasValue());
				$this->assertTrue(\tradeSystem\Math::eq($MACDHistogramm->getValue(), $assertValue));
			}

			$chart->rollbackIndicatorsLastValue();
			$this->assertTrue(\tradeSystem\Math::eq($MACDHistogramm->getValue(), 0.23116));

			$chart->handleIndicatorsBar($lastBar);
			$this->assertTrue(\tradeSystem\Math::eq($MACDHistogramm->getValue(), 0.2663));
		}
	}
?>