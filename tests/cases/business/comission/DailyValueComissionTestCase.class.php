<?php
	namespace tradeSystem\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DailyValueComissionTestCase extends TradeSystemTestCase
	{
		public function setUp()
		{
			$this->saveSingleton(\tradeSystem\Portfolio::me());
			$this->saveSingleton(\tradeSystem\Log::me());
			$this->saveSingleton(\tradeSystem\DateTimeManager::me());
		}

		public function tearDown()
		{
			$this->restoreSingleton(\tradeSystem\Portfolio::me());
			$this->restoreSingleton(\tradeSystem\Log::me());
			$this->restoreSingleton(\tradeSystem\DateTimeManager::me());
		}

		public function testCommon()
		{
			$comission =
				\tradeSystem\DailyValueComission::create()->
				setPercent(1);

			\tradeSystem\Portfolio::me()->setBalance(10000);

			\tradeSystem\DateTimeManager::me()->setNow(
				\ewgraFramework\DateTime::create('2010-11-11 10:00:00')
			);

			$comission->manage();

			\tradeSystem\Portfolio::me()->subBalance(1000);

			\tradeSystem\DateTimeManager::me()->setNow(
				\ewgraFramework\DateTime::create('2010-11-11 11:00:00')
			);

			$comission->manage();

			\tradeSystem\Portfolio::me()->addBalance(1000);

			$this->assertEquals(10000, \tradeSystem\Portfolio::me()->getBalance());

			\tradeSystem\DateTimeManager::me()->setNow(
				\ewgraFramework\DateTime::create('2010-11-12 10:00:00')
			);

			$comission->manage();

			$this->assertEquals(9980, \tradeSystem\Portfolio::me()->getBalance());

			\tradeSystem\DateTimeManager::me()->setNow(
				\ewgraFramework\DateTime::create('2010-11-12 11:00:00')
			);

			$comission->manage();

			$this->assertEquals(9980, \tradeSystem\Portfolio::me()->getBalance());

			\tradeSystem\Portfolio::me()->addBalance(1000);

			\tradeSystem\DateTimeManager::me()->setNow(
				\ewgraFramework\DateTime::create('2010-11-13 11:00:00')
			);

			$comission->manage();

			$this->assertEquals(10970, \tradeSystem\Portfolio::me()->getBalance());
		}
	}
?>