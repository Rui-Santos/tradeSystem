<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PositionType extends \ewgraFramework\Enumeration
	{
		const LONG	= 1;
		const SHORT	= 2;

		protected $names = array(
			self::LONG 	=> 'Long',
			self::SHORT	=> 'Short'
		);

		/**
		 * @return PositionType
		 */
		public static function long()
		{
			return new self(self::LONG);
		}

		/**
		 * @return PositionType
		 */
		public static function short()
		{
			return new self(self::SHORT);
		}

		public function getInvertor()
		{
			return
				$this->getId() == self::LONG
					? 1
					: -1;
		}
	}
?>