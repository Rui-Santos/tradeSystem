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
		 * @return Chart
		 */
		public static function create()
		{
			return new self;
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
		public function handleBar(Bar $bar)
		{
			$this->bars[] = $bar;

			if (count($this->bars) > $this->barLimit)
				array_shift($this->bars);

			foreach ($this->indicators as $indicator)
				$indicator->handleBar($bar);

			return $this;
		}

		/**
		 * @return Bar
		 */
		public function getBarFromEnd($offset = 0)
		{
			if ($offset > $this->getBarCount())
				throw new \Exception();

			return $this->bars[$this->getBarCount()-1-$offset];
		}
	}
?>