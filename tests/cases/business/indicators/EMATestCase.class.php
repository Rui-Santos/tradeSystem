<?php
	namespace tradeSystem\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class EMATestCase extends BaseIndicatorTest
	{
		public function testCommon()
		{
			$period = 10;

			$EMA = \tradeSystem\EMA::create($period);

			$series = $this->getSeries(1, $period-1);

			foreach ($series as $value)
				$EMA->handle($value);

			$this->assertFalse($EMA->hasValue());

			$series = $this->getSeries($period, 1);

			foreach ($series as $value)
				$EMA->handle($value);

			$this->assertTrue(\tradeSystem\Math::eq($EMA->getValue(), 101.933));

			$series = $this->getSeries($period+1, 1);

			foreach ($series as $value)
				$EMA->handle($value);

			$this->assertTrue(\tradeSystem\Math::eq($EMA->getValue(), 101.91791));
		}
	}
?>