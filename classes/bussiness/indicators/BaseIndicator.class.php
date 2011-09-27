<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseIndicator implements Indicator
	{
		private $history = null;
		private $historyLimit = null;

		private $value = null;

		/**
		 * @return BaseIndicator
		 */
		public function __construct()
		{

		}

		/**
		 * @return BaseIndicator
		 */
		protected function setValue($value)
		{
			$this->value = $value;
			return $this;
		}

		public function getValue()
		{
			return $this->value;
		}

		public function hasValue()
		{
			return $this->value !== null;
		}

		/**
		 * @return BaseIndicator
		 */
		public function handleBar(Bar $bar)
		{
			return $this->handle($bar->getClose());
		}
	}
?>