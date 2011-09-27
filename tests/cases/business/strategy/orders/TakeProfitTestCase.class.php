<?php
	namespace tradeSystem\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class TakeProfitTestCase extends TradeSystemTestCase
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

		public function testSellPercentIndent()
		{
			return $this->basePercentIndentTest(\tradeSystem\OrderType::sell());
		}

		public function testBuyPercentIndent()
		{
			return $this->basePercentIndentTest(\tradeSystem\OrderType::buy());
		}

		private function baseTest(\tradeSystem\OrderType $orderType)
		{
			$balance = 15001;
			$takePrice = 150;
			$priceIndent = 2;
			$count = 10;

			$priceList = array();
			$newBalance = null;
			$haveCount = null;
			$haveCountPrice = 100;

			if ($orderType->getId() == \tradeSystem\OrderType::SELL) {
				$priceList = array(
					149.01,
					147.01,
					149.01,
					160.01,
					165.01,
					163.01
				);

				$newBalance = 14348.96;
				$haveCount = 3;
				$newCount = -7;
			} else {
				$priceList = array(
					150.01,
					152.01,
					150.01,
					140.01,
					135.01,
					137.01
				);

				$newBalance = 14230.9;
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
				\tradeSystem\TakeProfit::create()->
				setSecurity($security)->
				setType($orderType)->
				setPrice($takePrice)->
				setIndent($priceIndent)->
				setIndentUnitsType(\tradeSystem\UnitsType::value())->
				setCount($count);

			foreach ($priceList as $price) {
				$order->handle($price);

				if ($price != $realizationPrice) {
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

		private function basePercentIndentTest(\tradeSystem\OrderType $orderType)
		{
			$balance = 15001;
			$takePrice = 150;
			$priceIndent = 2;
			$count = 10;

			$priceList = array();
			$newBalance = null;
			$haveCount = null;
			$haveCountPrice = 100;

			if ($orderType->getId() == \tradeSystem\OrderType::SELL) {
				$priceList = array(
					149.01,
					147.01,
					149.01,
					160.01,
					165.01,
					163.01,
					161.7098
				);

				$newBalance = 14354.1608;
				$haveCount = 3;
				$newCount = -7;
			} else {
				$priceList = array(
					150.01,
					152.01,
					150.01,
					140.01,
					135.01,
					137.01,
					137.7102
				);

				$newBalance = 14223.898;
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
				\tradeSystem\TakeProfit::create()->
				setSecurity($security)->
				setType($orderType)->
				setPrice($takePrice)->
				setIndent($priceIndent)->
				setIndentUnitsType(\tradeSystem\UnitsType::percent())->
				setCount($count);

			foreach ($priceList as $price) {
				$order->handle($price);

				if ($price != $realizationPrice) {
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