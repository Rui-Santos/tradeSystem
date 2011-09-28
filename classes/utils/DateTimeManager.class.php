<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DateTimeManager extends \ewgraFramework\Singleton
	{
		/**
		 * @var \ewgraframework\DateTime
		 */
		private $now = null;

		/**
		 * @return DateTimeManager
		 */
		public function setNow(\ewgraframework\DateTime $now)
		{
			$this->now = $now;
			return $this;
		}

		/**
		 * @return \ewgraFramework\DateTime
		 */
		public function getNow()
		{
			return
				$this->now
					? $this->now
					: \ewgraFramework\DateTime::makeNow();
		}
	}
?>