<?php
	namespace tradeSystem\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class EMATestCase extends TradeSystemTestCase
	{
		public function testCommon()
		{
			$period = 10;

			$EMA = \tradeSystem\EMA::create($period);

			$barReader =
				\tradeSystem\FinamBarReader::create()->
				setFileName(CASES_DIR."/input/SBER_110601_110901.txt")->
				skipHead();

			while(($bar = $barReader->getNext()) && $barReader->getRow() < $period)
				$EMA->handle($bar->getClose());

			$this->assertFalse($EMA->hasValue());

			$EMA->handle($barReader->getNext()->getClose());

			$this->assertTrue($EMA->hasValue());
			$this->assertTrue(\tradeSystem\Math::eq($EMA->getValue(), 97.215));

			$EMA->handle($barReader->getNext()->getClose());

			$this->assertTrue(\tradeSystem\Math::eq($EMA->getValue(), 96.98138));
		}
	}
?>