<?php
	namespace tradeSystem\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class FinamBarReaderTestCase extends TradeSystemTestCase
	{
		public function testCommon()
		{
			$barReader =
				\tradeSystem\FinamBarReader::create()->
				setFileName(CASES_DIR."/input/SBER_110601_110901.txt")->
				skipHead();

			$assertBar =
				\tradeSystem\Bar::create()->
				setOpen(98.00000)->
				setHigh(98.04000)->
				setLow(97.62000)->
				setClose(98.01000)->
				setDateTime(\ewgraFramework\DateTime::create('20110601 100000'));

			$bar = $barReader->getNext();

			$this->assertEquals($assertBar, $bar);

			$assertBar =
				\tradeSystem\Bar::create()->
				setOpen(98.01000)->
				setHigh(98.11000)->
				setLow(97.27000)->
				setClose(97.59000)->
				setDateTime(\ewgraFramework\DateTime::create('20110601 110000'));

			$bar = $barReader->getNext();

			$this->assertEquals($assertBar, $bar);
		}
	}
?>