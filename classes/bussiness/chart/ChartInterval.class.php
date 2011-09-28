<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ChartInterval extends \ewgraFramework\Enumeration
	{
		const HOURLY	= 1;

		protected $names = array(
			self::HOURLY 	=> 'Hourly'
		);

		/**
		 * @return ChartInterval
		 */
		public static function hourly()
		{
			return new self(self::HOURLY);
		}

		public function floorBarDateTime(Bar $bar)
		{
			$result = null;

			if ($this->getId() == self::HOURLY) {
				$result =
					\ewgraFramework\DateTime::create(
						$bar->getDateTime()->format('Y-m-d H:00:00')
					);
			} else
				throw new \Exception();

			return $result;
		}
	}
?>