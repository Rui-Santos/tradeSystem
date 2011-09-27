<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Position
	{
		private $id = null;

		/**
		 * @var Security
		 */
		private $security = null;

		/**
		 * @var PositionType
		 */
		private $type = null;

		private $price = null;
		private $count = null;

		/**
		 * @return Position
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return Position
		 */
		public function setId($id)
		{
			$this->id = $id;
			return $this;
		}

		public function getId()
		{
			return $this->id;
		}

		/**
		 * @return Position
		 */
		public function setSecurity(Security $security)
		{
			$this->security = $security;
			return $this;
		}

		/**
		 * @return Security
		 */
		public function getSecurity()
		{
			return $this->security;
		}

		/**
		 * @return Position
		 */
		public function setType(PositionType $type)
		{
			$this->type = $type;
			return $this;
		}

		/**
		 * @return PositionType
		 */
		public function getType()
		{
			return $this->type;
		}

		/**
		 * @return Position
		 */
		public function setPrice($price)
		{
			$this->price = $price;
			return $this;
		}

		public function getPrice()
		{
			return $this->price;
		}

		/**
		 * @return Position
		 */
		public function setCount($count)
		{
			$this->count = $count;
			return $this;
		}

		/**
		 * @return Position
		 */
		public function subCount($count)
		{
			$this->count -= $count;
			return $this;
		}

		public function getCount()
		{
			return $this->count;
		}

		public function getOpenSum()
		{
			return $this->getPrice()*$this->getCount();
		}

		public function getCloseSum($closePrice, $count)
		{
			return
				$this->getType()->getId() == PositionType::LONG
					? $closePrice*$count
					: (2*$this->getPrice()-$closePrice)*$count;
		}

		public function isOpposite(Position $position)
		{
			return $this->getType()->getId() != $position->getType()->getId();
		}
	}
?>