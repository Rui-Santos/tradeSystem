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
				addIndicator($shortEMA)->
				addIndicator($longEMA)->
				addIndicator($MACD)->
				addIndicator($MACDSignal)->
				addIndicator($MACDHistogramm);

			$barReader =
				\tradeSystem\FinamBarReader::create()->
				setFileName(CASES_DIR."/input/SBER_110601_110901.txt")->
				skipHead();

			while(
				($bar = $barReader->getNext())
				&& $barReader->getRow() < ($longEMA->getPeriod()+$MACDSignal->getEMAPeriod()-1)
			)
				$chart->handleBar($bar);

			$this->assertFalse($MACDHistogramm->hasValue());

			$assertValues = array(
				0.12384, 0.19107, 0.21427, 0.23116, 0.2663
			);

			foreach ($assertValues as $assertValue) {
				$chart->handleBar($barReader->getNext());
				$this->assertTrue($MACDHistogramm->hasValue());
				$this->assertTrue(\tradeSystem\Math::eq($MACDHistogramm->getValue(), $assertValue));
			}
		}
	}
?>