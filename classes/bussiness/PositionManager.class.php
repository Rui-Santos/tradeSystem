<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PositionManager extends \ewgraFramework\Singleton
	{
		/**
		 * @return PositionManager
		 */
		public static function me()
		{
			return parent::me();
		}

		/**
		 * @return PositionManager
		 */
		public function manage(Position $position)
		{
			$existPositions = PositionStorage::me()->getList($position->getSecurity());

			foreach ($existPositions as $existPosition) {
				if ($position->isOpposite($existPosition)) {
					$countToClose =
						$existPosition->getCount() >= $position->getCount()
							? $position->getCount()
							: $existPosition->getCount();

					$this->close($existPosition, $countToClose, $position->getPrice());

					$position->subCount($countToClose);

					if ($position->getCount() == 0)
						break;
				}
			}

			if ($position->getCount()) {
				PositionStorage::me()->add($position);

				Portfolio::me()->subBalance($position->getOpenSum());
			}

			return $this;
		}

		public function getTotalCount(Security $security)
		{
			$result = 0;

			$existPositions = PositionStorage::me()->getList($security);

			foreach ($existPositions as $existPosition) {
				$result +=
					$existPosition->getCount()
					*$existPosition->getType()->getInvertor();
			}

			return $result;
		}

		/**
		 * @return PositionManager
		 */
		public function closeAll(Security $security, $closePrice)
		{
			$existPositions = PositionStorage::me()->getList($security);

			foreach ($existPositions as $existPosition)
				$this->close($existPosition, $existPosition->getCount(), $closePrice);

			return $this;
		}

		/**
		 * @return PositionManager
		 */
		private function close(Position $position, $count, $closePrice)
		{
			$position->subCount($count);

			if ($position->getCount() == 0)
				PositionStorage::me()->drop($position);

			Portfolio::me()->addBalance(
				$position->getCloseSum($closePrice, $count)
			);

			return $this;
		}
	}
?>