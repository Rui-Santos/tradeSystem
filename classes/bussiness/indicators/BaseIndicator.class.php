<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseIndicator implements Indicator
	{
		private $history = array();
		private $historyLimit = 1;

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
		public function handle($value)
		{
			if ($this->hasValue()) {
				$this->history[] = $this->getValue();

				if (
					$this->historyLimit
					&& count($this->history) > $this->historyLimit
				)
					array_shift($this->historyLimit);
			}

			return $this;
		}

		/**
		 * @return BaseIndicator
		 */
		public function handleBar(Bar $bar)
		{
			return $this->handle($bar->getClose());
		}

		public function rollbackLastValue()
		{
			$this->setValue($this->history[count($this->history)-1]);
			array_pop($this->history);
			return $this;
		}
	}
?>