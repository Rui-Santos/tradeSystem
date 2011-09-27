<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PositionStorage extends \ewgraFramework\Singleton
	{
		private $positionPrimaryKey = 0;

		private $positions = array();

		/**
		 * @return PositionStorage
		 */
		public static function me()
		{
			return parent::me();
		}

		public function has(Security $security)
		{
			return isset($this->positions[$security->getId()]);
		}

		public function getList(Security $security)
		{
			return
				$this->has($security)
					? $this->positions[$security->getId()]
					: null;
		}

		/**
		 * @return PositionStorage
		 */
		public function add(Position $position)
		{
			$position->setId($this->positionPrimaryKey++);

			if (!isset($this->positions[$position->getSecurity()->getId()]))
				$this->positions[$position->getSecurity()->getId()] = array();

			$this->positions[$position->getSecurity()->getId()][$position->getId()] =
				$position;

			return $this;
		}

		/**
		 * @return PositionStorage
		 */
		public function drop(Position $position)
		{
			unset($this->positions[$position->getSecurity()->getId()][$position->getId()]);

			return $this;
		}
	}
?>