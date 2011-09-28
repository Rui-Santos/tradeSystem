<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Log extends \ewgraFramework\Singleton
	{
		private $items = array();

		/**
		 * @return Log
		 */
		public function add($text)
		{
			$this->items[] = $text;
			return $this;
		}
	}
?>