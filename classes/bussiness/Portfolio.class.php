<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Portfolio extends \ewgraFramework\Singleton
	{
		private $balance = null;

		private $value = null;

		/**
		 * @return Portfolio
		 */
		public function setBalance($balance, $dropValue = true)
		{
			$this->balance = $balance;

			if ($dropValue)
				$this->value = 0;

			return $this;
		}

		/**
		 * @return Portfolio
		 */
		public function addBalance($balance, $addValue = true)
		{
			$this->balance = Math::add($this->balance, $balance);

			Log::me()->add(
				DateTimeManager::me()->getNow()->format('Y-m-d H:i:s').' '
				.__CLASS__.': add '.$balance.' to balance, now '.$this->balance
			);

			if ($addValue)
				$this->value += $balance;

			return $this;
		}

		/**
		 * @return Portfolio
		 */
		public function subBalance($balance, $addValue = true)
		{
			$this->balance = Math::sub($this->balance, $balance);

			Log::me()->add(
				DateTimeManager::me()->getNow()->format('Y-m-d H:i:s').' '
				.__CLASS__.': sub '.$balance.' from balance, now '.$this->balance
			);

			if ($addValue)
				$this->value += $balance;

			return $this;
		}

		public function getBalance()
		{
			return $this->balance;
		}

		public function getValue()
		{
			return $this->value;
		}
	}
?>