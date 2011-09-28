<?php
	namespace tradeSystem;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class SeriesCounter
	{
		private $winSeries = 0;
		private $loseSeries = 0;

		private $win 	= 0;
		private $lose	= 0;

		public static function create()
		{
			return new self;
		}

		public function win()
		{
			if ($this->lose) {
				$this->loseSeries = max($this->loseSeries, $this->lose);
				$this->lose = 0;
			}

			$this->win++;
		}

		public function lose()
		{
			if ($this->win) {
				$this->winSeries = max($this->winSeries, $this->win);
				$this->win = 0;
			}

			$this->lose++;
		}

		public function getWinSeries()
		{
			return max($this->winSeries, $this->win);
		}

		public function getLoseSeries()
		{
			return max($this->loseSeries, $this->lose);
		}
	}
?>