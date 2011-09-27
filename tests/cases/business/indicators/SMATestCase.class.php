<?php
	namespace tradeSystem\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class SMATestCase extends TradeSystemTestCase
	{
		public function testCommon()
		{
			$period = 10;

			$SMA = \tradeSystem\SMA::create($period);

			$barReader =
				\tradeSystem\FinamBarReader::create()->
				setFileName(CASES_DIR."/input/SBER_110601_110901.txt")->
				skipHead();

			while(($bar = $barReader->getNext()) && $barReader->getRow() < $period)
				$SMA->handle($bar->getClose());

			$this->assertFalse($SMA->hasValue());

			$SMA->handle($barReader->getNext()->getClose());

			$this->assertTrue($SMA->hasValue());
			$this->assertTrue(\tradeSystem\Math::eq($SMA->getValue(), 97.215));

			$SMA->handle($barReader->getNext()->getClose());

			$this->assertTrue(\tradeSystem\Math::eq($SMA->getValue(), 97.007));
		}
	}
?>