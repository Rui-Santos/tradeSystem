<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseOrder extends BaseStrategy
	{
		private $realized 			= false;
		private $realizationPrice	= null;

		/**
		 * @var Security
		 */
		private $security = null;

		/**
		 * @var OrderType
		 */
		private $type	= null;

		private $count	= null;

		public function isRealized()
		{
			return $this->realized;
		}

		/**
		 * @return BaseOrder
		 */
		public function realized()
		{
			$this->realized = true;
			return $this;
		}

		/**
		 * @return BaseOrder
		 */
		public function setRealizationPrice($realizationPrice)
		{
			$this->realizationPrice = $realizationPrice;
			return $this;
		}

		public function getRealizationPrice()
		{
			return $this->realizationPrice;
		}

		/**
		 * @return BaseOrder
		 */
		public function setSecurity(Security $security)
		{
			$this->security = $security;
			return $this;
		}

		/**
		 * @var Security
		 */
		public function getSecurity()
		{
			return $this->security;
		}

		/**
		 * @return BaseOrder
		 */
		public function setType(OrderType $type)
		{
			$this->type = $type;
			return $this;
		}

		/**
		 * @var OrderType
		 */
		public function getType()
		{
			return $this->type;
		}

		/**
		 * @return BaseOrder
		 */
		public function setCount($count)
		{
			$this->count = $count;
			return $this;
		}

		public function getCount()
		{
			return $this->count;
		}

		/**
		 * @return BaseOrder
		 */
		public function realize($price)
		{
			$position =
				Position::create()->
				setCount($this->getCount())->
				setPrice($price)->
				setSecurity($this->getSecurity())->
				setType($this->getType()->getPositionType());

			PositionManager::me()->manage($position);

			$this->realized();
			$this->setRealizationPrice($price);

			$this->drop();

			return $this;
		}
	}
?>