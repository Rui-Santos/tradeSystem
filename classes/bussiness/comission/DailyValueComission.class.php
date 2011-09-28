<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DailyValueComission
	{
		private $percent = null;

		private $prevValue = null;
		private $prevDate = null;

		/**
		 * @return DailyValueComission
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return DailyValueComission
		 */
		public function setPercent($percent)
		{
			$this->percent = $percent;
			return $this;
		}

		/**
		 * @return DailyValueComission
		 */
		public function manage()
		{
			$now = DateTimeManager::me()->getNow();

			if (
				$this->prevDate
				&& $now->format('Y-m-d') != $this->prevDate->format('Y-m-d')
			) {
				$comission =
					Math::multiply(
						Math::sub(Portfolio::me()->getValue(), $this->prevValue),
						Math::div($this->percent, 100)
					);

				if ($comission) {
					Portfolio::me()->subBalance($comission, false);

					Log::me()->add(
						$now->format('Y-m-d H:i:s').' '
						.__CLASS__.': sub daily value commision '.$comission.' from balance, now '.Portfolio::me()->getBalance()
					);

					$this->prevValue = Portfolio::me()->getValue();
				}
			}

			if ($this->prevValue === null)
				$this->prevValue = Portfolio::me()->getValue();

			$this->prevDate = $now;
			return $this;
		}
	}
?>