<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Chart
	{
		private $indicators	= array();
		private $barLimit 	= null;
		private $bars 		= array();

		/**
		 * @var ChartInterval
		 */
		private $interval = null;

		private $groupedBars = 0;

		/**
		 * @return Chart
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return Chart
		 */
		public function setInterval(ChartInterval $interval)
		{
			$this->interval = $interval;
			return $this;
		}

		/**
		 * @return Chart
		 */
		public function setBarLimit($barLimit)
		{
			$this->barLimit = $barLimit;
			return $this;
		}

		public function getBarCount()
		{
			return count($this->bars);
		}

		/**
		 * @return Chart
		 */
		public function addIndicator(Indicator $indicator)
		{
			$this->indicators[] = $indicator;
			return $this;
		}

		/**
		 * @return Chart
		 */
		public function rollbackIndicatorsLastValue()
		{
			foreach ($this->indicators as $indicator)
				$indicator->rollbackLastValue();

			return $this;
		}

		/**
		 * @return Chart
		 */
		public function handleIndicatorsBar(Bar $bar)
		{
			foreach ($this->indicators as $indicator)
				$indicator->handleBar($bar);

			return $this;
		}

		/**
		 * @return Chart
		 */
		public function handleBar(Bar $bar)
		{
			foreach ($this->indicators as $indicator) {
				if($this->groupedBars)
					$indicator->rollbackLastValue();

				$indicator->handleBar($bar);
			}

			$barDateTime = $this->interval->floorBarDateTime($bar);

			$lastBar = $this->getBarFromEnd();

			if (
				!$lastBar
				|| $lastBar->getDateTime()->getTimestamp() !=
					$barDateTime->getTimestamp()
			) {
				$nextBar = clone $bar;
				$this->bars[] = $nextBar;
				$nextBar->setDateTime($barDateTime);

				$this->groupedBars = 0;
			} else {
				$this->groupedBars++;
				$lastBar->setLow(min($lastBar->getLow(), $bar->getLow()));
				$lastBar->setHigh(max($lastBar->getHigh(), $bar->getHigh()));
				$lastBar->setClose($bar->getClose());
			}

			if (
				$this->barLimit
				&& count($this->bars) > $this->barLimit
			)
				array_shift($this->bars);

			return $this;
		}

		/**
		 * @return Bar
		 */
		public function getBarFromEnd($offset = 0)
		{
			if (!$this->getBarCount())
				return null;

			if ($offset > $this->getBarCount())
				throw new \Exception();

			return $this->bars[$this->getBarCount()-1-$offset];
		}
	}
?>