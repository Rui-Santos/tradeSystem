<?php
	namespace tradeSystem\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class SMATestCase extends BaseIndicatorTest
	{
		public function testCommon()
		{
			$period = 10;

			$SMA = \tradeSystem\SMA::create($period);

			$series = $this->getSeries(1, $period-1);

			foreach ($series as $value)
				$SMA->handle($value);

			$this->assertFalse($SMA->hasValue());

			$series = $this->getSeries($period, 1);

			foreach ($series as $value)
				$SMA->handle($value);

			$this->assertTrue(\tradeSystem\Math::eq($SMA->getValue(), 101.933));

			$series = $this->getSeries($period+1, 1);

			foreach ($series as $value)
				$SMA->handle($value);

			$this->assertTrue(\tradeSystem\Math::eq($SMA->getValue(), 101.878));
		}
	}
?>