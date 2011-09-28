<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Portfolio extends \ewgraFramework\Singleton
	{
		// FIXME XXX: realize me
		# const BROKER_PAY = 0.001294;

		private $balance = null;

		/**
		 * @return Portfolio
		 */
		public function setBalance($balance)
		{
			$this->balance = $balance;
			return $this;
		}

		/**
		 * @return Portfolio
		 */
		public function addBalance($balance)
		{
			$this->balance = Math::add($this->balance, $balance);

			Log::me()->add(
				DateTimeManager::me()->getNow()->format('Y-m-d H:i:s').' '
				.__CLASS__.': add '.$balance.' to balance, now '.$this->balance
			);

			return $this;
		}

		/**
		 * @return Portfolio
		 */
		public function subBalance($balance)
		{
			$this->balance = Math::sub($this->balance, $balance);

			Log::me()->add(
				DateTimeManager::me()->getNow()->format('Y-m-d H:i:s').' '
				.__CLASS__.': sub '.$balance.' from balance, now '.$this->balance
			);

			return $this;
		}

		public function getBalance()
		{
			return $this->balance;
		}
	}
?>