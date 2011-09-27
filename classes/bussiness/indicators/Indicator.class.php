<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface Indicator
	{
		public function getValue();

		public function hasValue();

		/**
		 * @return Indicator
		 */
		public function handle($value);

		/**
		 * @return Indicator
		 */
		public function handleBar(Bar $bar);
	}
?>