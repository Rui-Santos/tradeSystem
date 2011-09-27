<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class UnitsType extends \ewgraFramework\Enumeration
	{
		const VALUE		= 1;
		const PERCENT	= 2;

		protected $names = array(
			self::VALUE 	=> 'Value',
			self::PERCENT	=> 'Percent'
		);

		/**
		 * @return UnitsType
		 */
		public static function value()
		{
			return new self(self::VALUE);
		}

		/**
		 * @return UnitsType
		 */
		public static function percent()
		{
			return new self(self::PERCENT);
		}
	}
?>