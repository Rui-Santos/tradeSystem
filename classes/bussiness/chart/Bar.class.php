<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Bar
	{
		private $open		= null;
		private $close		= null;
		private $high		= null;
		private $low		= null;

		/**
		 * @var \ewgraFramework\DateTime
		 */
		private $dateTime	= '';

		/**
		 * @return Bar
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return Bar
		 */
		public function setOpen($open)
		{
			$this->open = $open;
			return $this;
		}

		public function getOpen()
		{
			return $this->open;
		}

		/**
		 * @return Bar
		 */
		public function setClose($close)
		{
			$this->close = $close;
			return $this;
		}

		public function getClose()
		{
			return $this->close;
		}

		/**
		 * @return Bar
		 */
		public function setHigh($high)
		{
			$this->high = $high;
			return $this;
		}

		public function getHigh()
		{
			return $this->high;
		}

		/**
		 * @return Bar
		 */
		public function setLow($low)
		{
			$this->low = $low;
			return $this;
		}

		public function getLow()
		{
			return $this->low;
		}

		/**
		 * @return Bar
		 */
		public function setDateTime(\ewgraFramework\DateTime $dateTime)
		{
			$this->dateTime = $dateTime;
			return $this;
		}

		/**
		 * @return \ewgraFramework\DateTime
		 */
		public function getDateTime()
		{
			return $this->dateTime;
		}

		public function getMiddle()
		{
			return
				Math::sub(
					$this->getHigh(),
					Math::div(
						Math::sub($this->getHigh(), $this->getLow()),
						2
					)
				);
		}

		public function isWhite()
		{
			return Math::gt($this->getClose(), $this->getOpen());
		}

		public function isBlack()
		{
			return !$this->isWhite();
		}
	}
?>