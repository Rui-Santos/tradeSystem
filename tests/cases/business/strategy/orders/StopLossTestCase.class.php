<?php
	namespace tradeSystem\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class StopLossTestCase extends TradeSystemTestCase
	{
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

		public function testSell()
		{
			return $this->baseTest(\tradeSystem\OrderType::sell());
		}

		public function testBuy()
		{
			return $this->baseTest(\tradeSystem\OrderType::buy());
		}

		private function baseTest(\tradeSystem\OrderType $orderType)
		{
			$stopPrice = 150;
			$balance = 15001;

			$count = 10;

			$priceList = array();
			$newBalance = null;
			$haveCount = null;
			$haveCountPrice = 100;

			if ($orderType->getId() == \tradeSystem\OrderType::SELL) {
				$priceList = array(
					150.01,
					160,
					149.01
				);

				$newBalance = 14404.96;
				$haveCount = 3;
				$newCount = -7;
			} else {
				$priceList = array(
					149.01,
					140,
					150.01
				);

				$newBalance = 14100.9;
				$haveCount = -3;
				$newCount = 7;
			}

			$realizationPrice = end($priceList);

			\tradeSystem\Portfolio::me()->setBalance($balance);

			$security =
				\tradeSystem\Security::create()->
				setId(rand());

			\tradeSystem\PositionStorage::me()->
				add(
					\tradeSystem\Position::create()->
					setCount(abs($haveCount))->
					setPrice($haveCountPrice)->
					setSecurity($security)->
					setType($orderType->getInvertedPositionType())
				);

			$order =
				\tradeSystem\StopLoss::create()->
				setSecurity($security)->
				setType($orderType)->
				setPrice($stopPrice)->
				setCount($count);

			foreach ($priceList as $price) {
				$order->handle($price);

				if (\tradeSystem\Math::notEq($price, $realizationPrice)) {
					$this->assertSame(
						$haveCount,
						\tradeSystem\PositionManager::me()->getTotalCount($security)
					);

					$this->assertFalse($order->isRealized());
				}
			}

			$this->assertNotSame(
				$haveCount,
				\tradeSystem\PositionManager::me()->getTotalCount($security)
			);

			$this->assertSame(
				$realizationPrice,
				$order->getRealizationPrice()
			);

			$this->assertSame(
				$newCount,
				\tradeSystem\PositionManager::me()->getTotalCount($security)
			);

			$this->assertTrue($order->isRealized());

			$this->assertSame(
				\tradeSystem\Portfolio::me()->getBalance(),
				$newBalance
			);
		}
	}
?>