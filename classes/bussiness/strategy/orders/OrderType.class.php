<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class OrderType extends \ewgraFramework\Enumeration
	{
		const BUY	= 1;
		const SELL	= 2;

		protected $names = array(
			self::BUY 	=> 'Buy',
			self::SELL	=> 'Sell'
		);

		protected $positionType = array(
			self::BUY 	=> PositionType::LONG,
			self::SELL	=> PositionType::SHORT
		);

		protected $invertedPositionType = array(
			self::BUY 	=> PositionType::SHORT,
			self::SELL	=> PositionType::LONG
		);

		/**
		 * @return OrderType
		 */
		public static function buy()
		{
			return new self(self::BUY);
		}

		/**
		 * @return OrderType
		 */
		public static function sell()
		{
			return new self(self::SELL);
		}

		public function getPositionType()
		{
			return PositionType::create($this->positionType[$this->getId()]);
		}

		public function getInvertedPositionType()
		{
			return PositionType::create($this->invertedPositionType[$this->getId()]);
		}

		public function getInvertor()
		{
			return
				$this->getId() == self::BUY
					? 1
					: -1;
		}

		public function getInvertedInvertor()
		{
			return
				$this->getId() == self::SELL
					? 1
					: -1;
		}
	}
?>